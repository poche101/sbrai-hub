<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EspeesService
{
    private string $baseUrl;
    private string $apiKey;
    private string $secretKey;

    public function __construct()
    {
        $this->baseUrl   = config('services.espees.base_url', 'https://gateway.espees.org/api/v1');
        $this->apiKey    = config('services.espees.api_key', '');
        $this->secretKey = config('services.espees.secret_key', '');
    }

    public function debit(string $walletId, string $pin, int $amount, string $description, string $reference): array
    {
        try {
            $response = Http::withHeaders([
                'X-API-Key'    => $this->apiKey,
                'X-Secret-Key' => $this->secretKey,
                'Content-Type' => 'application/json',
            ])->timeout(30)->post("{$this->baseUrl}/transactions/debit", [
                'wallet_id'   => $walletId,
                'pin'         => $pin,
                'amount'      => $amount,
                'currency'    => 'ESPEES',
                'description' => $description,
                'reference'   => $reference,
            ]);

            $data = $response->json();

            if ($response->successful() && ($data['status'] ?? '') === 'success') {
                return [
                    'success'        => true,
                    'transaction_id' => $data['data']['transaction_id'] ?? $reference,
                    'balance'        => $data['data']['balance'] ?? 0,
                    'message'        => 'Payment successful',
                ];
            }

            return ['success' => false, 'message' => $data['message'] ?? 'Espees payment failed'];
        } catch (\Exception $e) {
            Log::error('Espees error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Payment service unavailable'];
        }
    }

    public function getBalance(string $walletId): ?float
    {
        try {
            $res = Http::withHeaders(['X-API-Key' => $this->apiKey])
                ->get("{$this->baseUrl}/wallets/{$walletId}/balance");
            return $res->successful() ? $res->json('data.balance') : null;
        } catch (\Exception $e) {
            return null;
        }
    }
}
