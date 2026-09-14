<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Paystack API integration.
 * Docs: https://paystack.com/docs/api/transaction/
 *
 * Flow:
 * 1. initializeTransaction() -> returns an authorization_url + reference
 * 2. Flutter opens authorization_url in a webview
 * 3. Paystack redirects to our callback_url with ?reference=...
 * 4. We call verifyTransaction($reference) to confirm payment server-side
 * 5. Paystack also POSTs a webhook on charge.success as a backup confirmation
 */
class PaystackService
{
    private string $baseUrl = 'https://api.paystack.co';
    private string $secretKey;

    public function __construct()
    {
        $this->secretKey = config('services.paystack.secret');
    }

    private function headers(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->secretKey,
            'Content-Type'  => 'application/json',
        ];
    }

    /**
     * Initialize a transaction. Amount is in kobo (₦20,000 = 2000000).
     */
    public function initializeTransaction(
        string $email,
        int $amountKobo,
        string $reference,
        string $callbackUrl,
        array $metadata = []
    ): array {
        try {
            $response = Http::withHeaders($this->headers())
                ->timeout(30)
                ->post("{$this->baseUrl}/transaction/initialize", [
                    'email'        => $email,
                    'amount'       => $amountKobo,
                    'reference'    => $reference,
                    'callback_url' => $callbackUrl,
                    'currency'     => 'NGN',
                    'metadata'     => $metadata,
                ]);

            $data = $response->json();

            if ($response->successful() && ($data['status'] ?? false)) {
                return [
                    'success'          => true,
                    'authorization_url' => $data['data']['authorization_url'],
                    'access_code'      => $data['data']['access_code'],
                    'reference'        => $data['data']['reference'],
                ];
            }

            return ['success' => false, 'message' => $data['message'] ?? 'Could not initialize transaction'];
        } catch (\Exception $e) {
            Log::error('Paystack initialize error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Payment service unavailable'];
        }
    }

    /**
     * Verify a transaction by reference. This is the source of truth —
     * always verify server-side before activating a subscription, never
     * trust the client's "I paid" claim or the redirect alone.
     */
    public function verifyTransaction(string $reference): array
    {
        try {
            $response = Http::withHeaders($this->headers())
                ->timeout(30)
                ->get("{$this->baseUrl}/transaction/verify/{$reference}");

            $data = $response->json();

            if ($response->successful() && ($data['status'] ?? false)) {
                $tx = $data['data'];
                return [
                    'success'        => $tx['status'] === 'success',
                    'status'         => $tx['status'],
                    'amount_kobo'    => $tx['amount'],
                    'currency'       => $tx['currency'],
                    'reference'      => $tx['reference'],
                    'paid_at'        => $tx['paid_at'] ?? null,
                    'customer_email' => $tx['customer']['email'] ?? null,
                    'metadata'       => $tx['metadata'] ?? [],
                ];
            }

            return ['success' => false, 'message' => $data['message'] ?? 'Verification failed'];
        } catch (\Exception $e) {
            Log::error('Paystack verify error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Verification service unavailable'];
        }
    }

    /**
     * Verify the webhook signature Paystack sends in the
     * X-Paystack-Signature header (HMAC-SHA512 of the raw body using
     * your secret key). Reject anything that doesn't match — this is
     * what stops anyone from faking a "payment succeeded" callback.
     */
    public function verifyWebhookSignature(string $payload, string $signature): bool
    {
        $expected = hash_hmac('sha512', $payload, $this->secretKey);
        return hash_equals($expected, $signature);
    }
}
