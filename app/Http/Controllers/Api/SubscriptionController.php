<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\ConfirmAccountMail;
use App\Mail\SubscriptionConfirmedMail;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\User;
use App\Services\EspeesService;
use App\Services\NotificationService;
use App\Services\PaystackService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SubscriptionController extends Controller
{
    private const PAYSTACK_AMOUNT_NGN = 20000;
    private const ESPEES_AMOUNT       = 10;
    private const WELCOME_VOUCHER_NGN = 5000;

    private PaystackService $paystack;
    private EspeesService $espees;

    public function __construct(PaystackService $paystack, EspeesService $espees)
    {
        $this->paystack = $paystack;
        $this->espees   = $espees;
    }

    // ─── Get Subscription Status ──────────────────────────────────────
    // `can_post` = KYC verified AND active subscription. The extra fields
    // let clients tell the two requirements apart instead of guessing.
    public function status(Request $request): JsonResponse
    {
        $user = $request->user();

        $subscription = $user->subscriptions()
            ->where('status', 'active')
            ->where('end_date', '>', now())
            ->latest('end_date')
            ->first();

        $kycVerified = $user->kyc_status === 'verified';

        $blockedReason = null;
        if (!$kycVerified) {
            $blockedReason = 'KYC_REQUIRED';
        } elseif (!$subscription) {
            $blockedReason = 'SUBSCRIPTION_REQUIRED';
        }

        return response()->json([
            'success'                 => true,
            'subscription'            => $subscription ? $this->formatSub($subscription) : null,
            'has_active_subscription' => $subscription !== null,
            'kyc_status'              => $user->kyc_status,
            'can_post'                => $kycVerified && $subscription !== null,
            'blocked_reason'          => $blockedReason,
        ]);
    }

    // ─── Paystack: Create a per-vendor checkout ───────────────────────
    // The transaction is created server-side with the vendor's id in the
    // metadata, so the webhook can activate the right account even if the
    // vendor closes the app/tab before returning.
    public function getPaystackCheckout(Request $request): JsonResponse
    {
        $user = $request->user();

        if (empty($user->email)) {
            return response()->json([
                'success' => false,
                'message' => 'Add an email address to your account before paying.',
            ], 422);
        }

        $reference = 'SBRAI-SUB-' . $user->id . '-' . now()->timestamp;

        $result = $this->paystack->initializeTransaction(
            email: $user->email,
            amountKobo: self::PAYSTACK_AMOUNT_NGN * 100,
            reference: $reference,
            callbackUrl: rtrim(config('app.url'), '/') . '/payments/callback',
            metadata: [
                'user_id' => (string) $user->id,
                'purpose' => 'vendor_subscription',
            ],
        );

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => $result['message'] ?? 'Could not start Paystack checkout',
            ], 502);
        }

        return response()->json([
            'success'      => true,
            'checkout_url' => $result['authorization_url'],
            'reference'    => $result['reference'],
            'amount'       => self::PAYSTACK_AMOUNT_NGN,
            'currency'     => 'NGN',
        ]);
    }

    // ─── Paystack: Client-side verify (reference from callback/receipt) ─
    public function handlePaystackCallback(Request $request): JsonResponse
    {
        $request->validate([
            'reference' => 'required|string',
        ]);

        $user = $request->user();

        try {
            // Paystack's API is the source of truth, never the client.
            $result = $this->paystack->verifyTransaction($request->reference);

            if (empty($result['success'])) {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'] ?? 'Payment verification failed',
                ], 422);
            }

            if ($error = $this->validatePaystackPayment($result, $user)) {
                return response()->json(['success' => false, 'message' => $error], 422);
            }

            [$subscription, $created] = $this->activateSubscription(
                $user,
                'paystack',
                $result['reference'],
                $result['amount_kobo'] / 100,
                'NGN',
            );

            if ($created) {
                $this->sendActivationNotices($user, $subscription, 'Paystack');
            }

            return response()->json([
                'success'      => true,
                'message'      => $created ? 'Subscription activated successfully' : 'Subscription already active for this payment.',
                'subscription' => $this->formatSub($subscription),
            ]);
        } catch (\RuntimeException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 409);
        } catch (\Throwable $e) {
            Log::error('Paystack verification crash: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Processing error occurred'], 500);
        }
    }

    // ─── Paystack: Webhook (charge.success) ───────────────────────────
    // Public route, authenticated by the X-Paystack-Signature header.
    // Always answers 200 for events we deliberately ignore so Paystack
    // doesn't keep retrying; answers 500 on real failures so it does.
    public function paystackWebhook(Request $request): JsonResponse
    {
        $signature = (string) $request->header('x-paystack-signature', '');

        if (!$this->paystack->verifyWebhookSignature($request->getContent(), $signature)) {
            return response()->json(['message' => 'Invalid signature'], 401);
        }

        if ($request->input('event') !== 'charge.success') {
            return response()->json(['message' => 'Ignored']);
        }

        $reference = (string) $request->input('data.reference', '');
        if ($reference === '') {
            return response()->json(['message' => 'No reference']);
        }

        try {
            // Re-verify with Paystack rather than trusting the payload.
            $result = $this->paystack->verifyTransaction($reference);

            if (empty($result['success'])) {
                Log::warning("Paystack webhook: {$reference} did not verify as successful.");
                return response()->json(['message' => 'Not successful']);
            }

            $user = $this->resolvePaystackUser($result);
            if (!$user) {
                Log::warning("Paystack webhook: no account matched payment {$reference}.");
                return response()->json(['message' => 'No matching account']);
            }

            if ($error = $this->validatePaystackPayment($result, $user)) {
                Log::warning("Paystack webhook: {$reference} rejected — {$error}");
                return response()->json(['message' => 'Rejected']);
            }

            [$subscription, $created] = $this->activateSubscription(
                $user,
                'paystack',
                $result['reference'],
                $result['amount_kobo'] / 100,
                'NGN',
            );

            if ($created) {
                $this->sendActivationNotices($user, $subscription, 'Paystack');
            }

            return response()->json(['message' => 'OK']);
        } catch (\RuntimeException $e) {
            Log::warning('Paystack webhook conflict: ' . $e->getMessage());
            return response()->json(['message' => 'Conflict']);
        } catch (\Throwable $e) {
            Log::error('Paystack webhook crash: ' . $e->getMessage());
            return response()->json(['message' => 'Processing error'], 500);
        }
    }

    // ─── Espees Gateway Payment ───────────────────────────────────────
    // Create a hosted checkout, remember the payment_ref against this
    // vendor (pending ledger row), and confirm it server-side later.
    public function espeesCheckout(Request $request): JsonResponse
    {
        $user       = $request->user();
        $productSku = 'SBRAI-SUB-' . $user->id . '-' . now()->timestamp;
        $base       = rtrim(config('app.url'), '/');

        $result = $this->espees->createProduct(
            productSku: $productSku,
            narration: 'Sbrai Solutions — Annual Vendor Subscription',
            price: self::ESPEES_AMOUNT,
            successUrl: $base . '/payments/callback?gateway=espees',
            failUrl: $base . '/payments/callback?gateway=espees&status=failed',
            userData: ['user_id' => (string) $user->id],
        );

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => $result['message'] ?? 'Could not start Espees checkout',
            ], 502);
        }

        // Tie this payment_ref to the vendor so /espees/verify can't be
        // used with someone else's reference.
        Transaction::create([
            'user_id'     => $user->id,
            'type'        => 'subscription',
            'amount'      => self::ESPEES_AMOUNT,
            'currency'    => 'ESPEES',
            'description' => 'Annual Subscription - Espees Gateway',
            'reference'   => $result['payment_ref'],
            'status'      => 'pending',
        ]);

        return response()->json([
            'success'      => true,
            'checkout_url' => $result['checkout_url'],
            'payment_ref'  => $result['payment_ref'],
        ]);
    }

    public function espeesVerify(Request $request): JsonResponse
    {
        $request->validate(['payment_ref' => 'required|string']);

        $user = $request->user();
        $ref  = $request->payment_ref;

        $ledger = Transaction::where('user_id', $user->id)
            ->where('type', 'subscription')
            ->where('reference', $ref)
            ->first();

        if (!$ledger) {
            return response()->json([
                'success' => false,
                'message' => 'Unknown payment reference for this account. Please start a new checkout.',
            ], 422);
        }

        if ($ledger->status === 'completed') {
            $existing = Subscription::where('vendor_id', $user->id)->where('transaction_id', $ref)->first();
            if ($existing) {
                return response()->json([
                    'success'      => true,
                    'message'      => 'Subscription already active for this payment.',
                    'subscription' => $this->formatSub($existing),
                ]);
            }
        }

        $result = $this->espees->confirmPayment($ref);

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
                    'PENDING' => 'Payment is still processing — try again in a moment.',
                    'DECLINE' => 'Payment was declined.',
                    default   => 'No payment found for that reference.',
                },
                'status' => $result['status'],
            ], 422);
        }

        if ($result['price'] !== null && (float) $result['price'] + 0.00001 < self::ESPEES_AMOUNT) {
            return response()->json([
                'success' => false,
                'message' => 'Payment amount is less than the annual subscription fee (10 Espees).',
            ], 422);
        }

        try {
            [$subscription, $created] = $this->activateSubscription(
                $user,
                'espees',
                $ref,
                self::ESPEES_AMOUNT,
                'ESPEES',
            );

            if ($created) {
                $this->sendActivationNotices($user, $subscription, 'Espees');
            }

            return response()->json([
                'success'      => true,
                'message'      => 'Subscription activated via Espees',
                'subscription' => $this->formatSub($subscription),
            ]);
        } catch (\RuntimeException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 409);
        } catch (\Throwable $e) {
            Log::error('Espees verify error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Processing error occurred'], 500);
        }
    }

    // ─── Transaction History ──────────────────────────────────────────
    public function transactions(Request $request): JsonResponse
    {
        $transactions = Transaction::where('user_id', $request->user()->id)
            ->where('status', '!=', 'pending')
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

    // ─── Core: activate a subscription exactly once per payment ───────
    // Used by the Paystack verify endpoint, the Paystack webhook and the
    // Espees verify endpoint, so a payment is never counted twice even if
    // the webhook and the client verify race each other.
    //
    // @return array{0: Subscription, 1: bool} [subscription, newlyCreated]
    private function activateSubscription(
        User $user,
        string $gateway,
        string $reference,
        float $amount,
        string $currency
    ): array {
        return DB::transaction(function () use ($user, $gateway, $reference, $amount, $currency) {
            // Serialize concurrent activations for the same vendor.
            User::whereKey($user->id)->lockForUpdate()->first();

            $existing = Subscription::where('transaction_id', $reference)->first();
            if ($existing) {
                if ((string) $existing->vendor_id !== (string) $user->id) {
                    throw new \RuntimeException('This payment reference has already been used by another account.');
                }
                return [$existing, false];
            }

            $isFirst = !$user->subscriptions()->exists();

            // Renewing early keeps the time already paid for.
            $current = $user->subscriptions()
                ->where('status', 'active')
                ->where('end_date', '>', now())
                ->latest('end_date')
                ->first();

            $endDate = ($current ? $current->end_date->copy() : now())->addYear();

            $user->subscriptions()->where('status', 'active')->update(['status' => 'inactive']);

            $subscription = Subscription::create([
                'vendor_id'       => $user->id,
                'status'          => 'active',
                'start_date'      => now(),
                'end_date'        => $endDate,
                'amount_paid'     => $amount,
                'payment_method'  => $gateway,
                'transaction_id'  => $reference,
                'payment_gateway' => $gateway,
            ]);

            $label = $gateway === 'espees' ? 'Espees Gateway' : 'Paystack';

            $ledger = Transaction::where('user_id', $user->id)
                ->where('type', 'subscription')
                ->where('reference', $reference)
                ->first();

            if ($ledger) {
                $ledger->update([
                    'amount'      => $amount,
                    'currency'    => $currency,
                    'description' => "Annual Subscription - {$label}",
                    'status'      => 'completed',
                ]);
            } else {
                Transaction::create([
                    'user_id'     => $user->id,
                    'type'        => 'subscription',
                    'amount'      => $amount,
                    'currency'    => $currency,
                    'description' => "Annual Subscription - {$label}",
                    'reference'   => $reference,
                    'status'      => 'completed',
                ]);
            }

            if ($isFirst) {
                $user->increment('voucher_balance', self::WELCOME_VOUCHER_NGN);
                Transaction::create([
                    'user_id'     => $user->id,
                    'type'        => 'voucher_credit',
                    'amount'      => self::WELCOME_VOUCHER_NGN,
                    'currency'    => 'NGN',
                    'description' => 'Welcome bonus - Sign up voucher',
                    'status'      => 'completed',
                ]);
            }

            return [$subscription, true];
        });
    }

    // ─── Emails + push (outside the DB transaction, never fatal) ──────
    private function sendActivationNotices(User $user, Subscription $subscription, string $gatewayLabel): void
    {
        try {
            Mail::to($user->email)->send(new SubscriptionConfirmedMail($user, $subscription));
        } catch (\Throwable $e) {
            Log::error("{$gatewayLabel} subscription email failed: " . $e->getMessage());
        }

        if (!$user->account_confirmed_at) {
            try {
                $user->update(['confirmation_token' => Str::random(64)]);
                Mail::to($user->email)->send(new ConfirmAccountMail($user));
            } catch (\Throwable $e) {
                Log::error('Account confirmation email failed: ' . $e->getMessage());
            }
        }

        try {
            NotificationService::sendPush(
                $user,
                'Subscription Activated! 🎉',
                "Your annual subscription via {$gatewayLabel} is active. Start posting listings!",
            );
        } catch (\Throwable $e) {
            Log::error('Subscription push failed: ' . $e->getMessage());
        }
    }

    // ─── Paystack helpers ─────────────────────────────────────────────

    /**
     * Returns an error message if the verified payment must NOT activate a
     * subscription for $user, or null if it's fine.
     */
    private function validatePaystackPayment(array $result, User $user): ?string
    {
        if (($result['currency'] ?? 'NGN') !== 'NGN') {
            return 'Unsupported payment currency.';
        }

        if (($result['amount_kobo'] ?? 0) < self::PAYSTACK_AMOUNT_NGN * 100) {
            return 'Payment amount is less than the annual subscription fee (₦20,000).';
        }

        $metadata = is_array($result['metadata'] ?? null) ? $result['metadata'] : [];

        // Payments created by getPaystackCheckout() carry the vendor's id.
        if (!empty($metadata['user_id'])) {
            return (string) $metadata['user_id'] === (string) $user->id
                ? null
                : 'This payment belongs to a different account.';
        }

        // Older payments made on the static Paystack page: match by email.
        return strcasecmp((string) ($result['customer_email'] ?? ''), (string) $user->email) === 0
            ? null
            : 'This payment was made with a different email address than your account.';
    }

    private function resolvePaystackUser(array $result): ?User
    {
        $metadata = is_array($result['metadata'] ?? null) ? $result['metadata'] : [];

        if (!empty($metadata['user_id'])) {
            return User::find($metadata['user_id']);
        }

        $email = (string) ($result['customer_email'] ?? '');
        if ($email === '') {
            return null;
        }

        return User::where('email', $email)->where('role', 'vendor')->first();
    }

    // ─── Formatting ───────────────────────────────────────────────────
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
