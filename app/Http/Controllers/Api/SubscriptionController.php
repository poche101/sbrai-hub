<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Services\PaystackService; // Swapped Stripe for Paystack
use App\Services\EspeesService;
use App\Services\NotificationService;
use App\Mail\SubscriptionConfirmedMail;
use App\Mail\ConfirmAccountMail;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SubscriptionController extends Controller
{
    private PaystackService $paystack;
    private EspeesService $espees;

    public function __construct(PaystackService $paystack, EspeesService $espees)
    {
        $this->paystack = $paystack;
        $this->espees = $espees;
    }

    // ─── Get Subscription Status ──────────────────────────────────────
    public function status(Request $request): JsonResponse
    {
        $subscription = $request->user()
            ->subscriptions()
            ->where('status', 'active')
            ->where('end_date', '>', now())
            ->latest()
            ->first();

        return response()->json([
            'success'      => true,
            'subscription' => $subscription ? $this->formatSub($subscription) : null,
            'can_post'     => $this->canPost($request->user()),
        ]);
    }

    // ─── Paystack: Get Hosted Checkout URL ────────────────────────────
    public function getPaystackCheckout(Request $request): JsonResponse
    {
        // Since you are using a static hosted payment page link, you can return
        // the link directly or dynamically pass customer references via query parameters.
        $checkoutUrl = env('PAYSTACK_PAYMENT_PAGE_URL', 'https://paystack.shop/pay/sbraisolutionsltd');

        // Optional: Append user email as a query parameter if your paystack page supports tracking it
        $finalUrl = $checkoutUrl . '?email=' . urlencode($request->user()->email);

        return response()->json([
            'success'      => true,
            'checkout_url' => $finalUrl,
            'amount'       => 20000,
            'currency'     => 'NGN',
        ]);
    }

    // ─── Paystack: Verify Webhook or Reference Callback ───────────────
    public function handlePaystackCallback(Request $request): JsonResponse
    {
        $request->validate([
            'reference' => 'required|string',
        ]);

        try {
            // Verify payment directly against Paystack's API
            $result = $this->paystack->verifyTransaction($request->reference);

            if (!$result || !isset($result['data']) || $result['data']['status'] !== 'success') {
                return response()->json(['success' => false, 'message' => 'Payment verification failed'], 422);
            }

            $paystackData = $result['data'];
            $amountPaid = $paystackData['amount'] / 100; // Convert kobo back to Naira

            // Prevent duplicate transaction entry tracking
            $existingTx = Transaction::where('reference', $request->reference)->exists();
            if ($existingTx) {
                return response()->json(['success' => true, 'message' => 'Subscription already processed.']);
            }

            return DB::transaction(function () use ($request, $paystackData, $amountPaid) {
                $user = $request->user();

                // Deactivate old subscriptions
                $user->subscriptions()->where('status', 'active')->update(['status' => 'inactive']);

                // Create new subscription record
                $subscription = Subscription::create([
                    'vendor_id'        => $user->id,
                    'status'           => 'active',
                    'start_date'       => now(),
                    'end_date'         => now()->addYear(),
                    'amount_paid'      => $amountPaid,
                    'payment_method'   => 'paystack',
                    'transaction_id'   => $paystackData['id'],
                    'payment_gateway'  => 'paystack',
                ]);

                // Log the payment transaction
                Transaction::create([
                    'user_id'     => $user->id,
                    'type'        => 'subscription',
                    'amount'      => $amountPaid,
                    'currency'    => 'NGN',
                    'description' => 'Annual Subscription - Paystack',
                    'reference'   => $paystackData['reference'],
                    'status'      => 'completed',
                ]);

                // Welcome voucher setup
                if ($user->subscriptions()->count() === 1) {
                    $user->increment('voucher_balance', 5000);
                    Transaction::create([
                        'user_id'     => $user->id,
                        'type'        => 'voucher_credit',
                        'amount'      => 5000,
                        'currency'    => 'NGN',
                        'description' => 'Welcome bonus - Sign up voucher',
                        'status'      => 'completed',
                    ]);
                }

                // Send subscription confirmation email
                try {
                    Mail::to($user->email)->send(new SubscriptionConfirmedMail($user, $subscription));
                } catch (\Exception $e) {
                    Log::error('Paystack subscription email failed: ' . $e->getMessage());
                }

                // Send account confirmation email if applicable
                if (!$user->account_confirmed_at) {
                    $user->update(['confirmation_token' => Str::random(64)]);
                    try {
                        Mail::to($user->email)->send(new ConfirmAccountMail($user));
                    } catch (\Exception $e) {
                        Log::error('Account confirmation email failed: ' . $e->getMessage());
                    }
                }

                // Fire push notification
                NotificationService::sendPush(
                    $user,
                    'Subscription Activated! 🎉',
                    'Your annual subscription via Paystack is active. Start posting listings!',
                );

                return response()->json([
                    'success'      => true,
                    'message'      => 'Subscription activated successfully',
                    'subscription' => $this->formatSub($subscription),
                ]);
        });

        } catch (\Exception $e) {
            Log::error('Paystack verification crash: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Processing error occurred'], 500);
        }
    }

    // ─── Espees Gateway Payment ───────────────────────────────────────
    // Rebuilt against Espees' real, documented hosted-checkout flow —
    // the previous version called a wallet+PIN debit endpoint that
    // doesn't exist in their actual API. This now mirrors the existing
    // Paystack pattern above: create a checkout, redirect the user to
    // Espees' own payment portal (their PIN never touches our server),
    // then confirm the payment server-side once they're done.
    public function espeesCheckout(Request $request): JsonResponse
    {
        $user = $request->user();
        $productSku = 'SBRAI-SUB-' . $user->id . '-' . now()->timestamp;

        $result = $this->espees->createProduct(
            productSku: $productSku,
            narration: 'Sbrai Solutions — Annual Vendor Subscription',
            price: 10,
            successUrl: config('app.url') . '/pricing?espees=success',
            failUrl: config('app.url') . '/pricing?espees=failed',
            userData: ['user_id' => $user->id],
        );

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => $result['message'] ?? 'Could not start Espees checkout',
            ], 502);
        }

        return response()->json([
            'success'      => true,
            'checkout_url' => $result['checkout_url'],
            'payment_ref'  => $result['payment_ref'],
        ]);
    }

    public function espeesVerify(Request $request): JsonResponse
    {
        $request->validate(['payment_ref' => 'required|string']);

        $result = $this->espees->confirmPayment($request->payment_ref);

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => $result['message'] ?? 'Could not verify Espees payment',
            ], 502);
        }

        if ($result['status'] !== 'APPROVED') {
            return response()->json([
                'success' => false,
                'message' => match ($result['status']) {
                    'PENDING'   => 'Payment is still processing — try again in a moment.',
                    'DECLINE'   => 'Payment was declined.',
                    default     => 'No payment found for that reference.',
                },
                'status' => $result['status'],
            ], 422);
        }

        try {
            return DB::transaction(function () use ($request, $result) {
                $user = $request->user();

                $user->subscriptions()->where('status', 'active')->update(['status' => 'inactive']);

                $subscription = Subscription::create([
                    'vendor_id'       => $user->id,
                    'status'          => 'active',
                    'start_date'      => now(),
                    'end_date'        => now()->addYear(),
                    'amount_paid'     => 10,
                    'payment_method'  => 'espees',
                    'transaction_id'  => $request->payment_ref,
                    'payment_gateway' => 'espees',
                ]);

                Transaction::create([
                    'user_id'     => $user->id,
                    'type'        => 'subscription',
                    'amount'      => 10,
                    'currency'    => 'ESPEES',
                    'description' => 'Annual Subscription - Espees Gateway',
                    'reference'   => $request->payment_ref,
                    'status'      => 'completed',
                ]);

                if ($user->subscriptions()->count() === 1) {
                    $user->increment('voucher_balance', 5000);
                }

                try {
                    Mail::to($user->email)->send(new SubscriptionConfirmedMail($user, $subscription));
                } catch (\Exception $e) {
                    Log::error('Espees subscription email failed: ' . $e->getMessage());
                }

                NotificationService::sendPush(
                    $user,
                    'Subscription Activated via Espees! 🌟',
                    'Your annual subscription is now active. Post your first listing!',
                );

                return response()->json([
                    'success'      => true,
                    'message'      => 'Subscription activated via Espees',
                    'subscription' => $this->formatSub($subscription),
                ]);
            });
        } catch (\Exception $e) {
            Log::error('Espees verify error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Processing error occurred'], 500);
        }
    }

    // ─── Transaction History ──────────────────────────────────────────
    public function transactions(Request $request): JsonResponse
    {
        $transactions = Transaction::where('user_id', $request->user()->id)
            ->orderByDesc('created_at')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data'    => $transactions->items(),
            'meta'    => [
                'current_page' => $transactions->currentPage(),
                'last_page'    => $transactions->lastPage(),
                'total'        => $transactions->total(),
            ],
        ]);
    }

    // ─── Voucher Balance ──────────────────────────────────────────────
    public function voucherBalance(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'balance' => $request->user()->voucher_balance ?? 0,
        ]);
    }

    // ─── Helpers ──────────────────────────────────────────────────────
    private function canPost($user): bool
    {
        return $user->kyc_status === 'verified' &&
               $user->subscriptions()
                    ->where('status', 'active')
                    ->where('end_date', '>', now())
                    ->exists();
    }

    private function formatSub(Subscription $sub): array
    {
        return [
            'id'             => $sub->id,
            'vendor_id'      => $sub->vendor_id,
            'status'         => $sub->status,
            'start_date'     => $sub->start_date->toISOString(),
            'end_date'       => $sub->end_date->toISOString(),
            'amount_paid'    => $sub->amount_paid,
            'payment_method' => $sub->payment_method,
            'transaction_id' => $sub->transaction_id,
        ];
    }
}
