<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Rebuilt against the real Espees API (developers.espees.org — confirmed
 * directly from their own docs, not guessed). The previous version of
 * this service called a direct wallet+PIN debit endpoint that doesn't
 * exist in Espees' actual API — their real flow is a hosted-checkout
 * redirect, the same pattern already used for Paystack elsewhere in this
 * app: create a "product" server-side, redirect the user to Espees' own
 * payment portal to enter their PIN there (never on our site), then
 * confirm the payment server-side afterward.
 */
class EspeesService
{
    private string $baseUrl;
    private string $paymentPortalUrl;
    private string $apiKey;
    private string $merchantWallet;

    public function __construct()
    {
        $this->baseUrl          = rtrim(config('services.espees.base_url', 'https://api.espees.org/v2'), '/');
        $this->paymentPortalUrl = rtrim(config('services.espees.payment_portal_url', 'https://payment.espees.org/pay'), '/');
        $this->apiKey           = config('services.espees.api_key', '');
        $this->merchantWallet   = config('services.espees.merchant_wallet', '');
    }

    private function headers(): array
    {
        return [
            'Content-Type' => 'application/json',
            'x-api-key'    => $this->apiKey,
        ];
    }

    /**
     * Step 1 of Espees' documented flow: create a payment "product" for
     * this specific charge. Returns a payment_ref plus the hosted
     * checkout URL to redirect the user to (payment_portal_url/{ref}).
     *
     * @param string $productSku Unique per charge — Espees requires this,
     *                            we generate it (e.g. "SBRAI-SUB-{userId}-{timestamp}").
     */
    public function createProduct(
        string $productSku,
        string $narration,
        float $price,
        string $successUrl,
        string $failUrl,
        array $userData = []
    ): array {
        try {
            $response = Http::withHeaders($this->headers())
                ->timeout(30)
                ->post("{$this->baseUrl}/payment/product", [
                    'product_sku'     => $productSku,
                    'narration'       => $narration,
                    'price'           => $price,
                    'merchant_wallet' => $this->merchantWallet,
                    'success_url'     => $successUrl,
                    'fail_url'        => $failUrl,
                    'user_data'       => $userData,
                ]);

            $data = $response->json();

            if ($response->successful() && (int) ($data['statusCode'] ?? 0) === 200 && !empty($data['payment_ref'])) {
                return [
                    'success'      => true,
                    'payment_ref'  => $data['payment_ref'],
                    'checkout_url' => "{$this->paymentPortalUrl}/{$data['payment_ref']}",
                ];
            }

            return ['success' => false, 'message' => $data['message'] ?? 'Could not create Espees payment'];
        } catch (\Exception $e) {
            Log::error('Espees createProduct: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Espees service unavailable'];
        }
    }

    /**
     * Step 3 of Espees' documented flow: check the actual status of a
     * payment_ref. transaction_status is one of: APPROVED, DECLINE,
     * PENDING, NOT FOUND (Espees' exact values, not ours).
     */
    public function confirmPayment(string $paymentRef): array
    {
        try {
            $response = Http::withHeaders($this->headers())
                ->timeout(30)
                ->post("{$this->baseUrl}/payment/confirm", [
                    'payment_ref' => $paymentRef,
                ]);

            $data = $response->json();

            if ($response->successful()) {
                return [
                    'success' => true,
                    'status'  => $data['transaction_status'] ?? 'NOT FOUND',
                    'price'   => $data['price'] ?? null,
                    'raw'     => $data,
                ];
            }

            return ['success' => false, 'message' => $data['message'] ?? 'Could not confirm Espees payment'];
        } catch (\Exception $e) {
            Log::error('Espees confirmPayment: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Espees service unavailable'];
        }
    }
}
