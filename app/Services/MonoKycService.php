<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MonoKycService
{
    private string $baseUrl = 'https://api.mono.co';
    private string $secretKey;

    public function __construct()
    {
        $this->secretKey = config('services.mono.secret_key', '');
    }

    private function headers(): array
    {
        return ['mono-sec-key' => $this->secretKey, 'Content-Type' => 'application/json'];
    }

    public function verifyNin(string $nin): array
    {
        try {
            $res  = Http::withHeaders($this->headers())->timeout(30)
                ->post("{$this->baseUrl}/v2/lookup/nin/verify", ['nin' => $nin]);
            $data = $res->json();
            if ($res->successful() && isset($data['data'])) {
                return ['success' => true, 'data' => [
                    'full_name'     => trim(($data['data']['firstname'] ?? '') . ' ' . ($data['data']['lastname'] ?? '')),
                    'date_of_birth' => $data['data']['date_of_birth'] ?? '',
                    'phone_number'  => $data['data']['phone'] ?? '',
                    'gender'        => $data['data']['gender'] ?? '',
                    'address'       => $data['data']['residential_address'] ?? '',
                    'photo'         => $data['data']['photo'] ?? '',
                ]];
            }
            return ['success' => false, 'message' => $data['message'] ?? 'NIN verification failed'];
        } catch (\Exception $e) {
            Log::error('Mono NIN: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Service unavailable'];
        }
    }

    public function verifyBvn(string $bvn): array
    {
        try {
            $res  = Http::withHeaders($this->headers())->timeout(30)
                ->post("{$this->baseUrl}/v2/lookup/bvn/verify", ['bvn' => $bvn]);
            $data = $res->json();
            if ($res->successful() && isset($data['data'])) {
                return ['success' => true, 'data' => [
                    'full_name'     => trim(($data['data']['first_name'] ?? '') . ' ' . ($data['data']['last_name'] ?? '')),
                    'date_of_birth' => $data['data']['date_of_birth'] ?? '',
                    'phone_number'  => $data['data']['phone_number1'] ?? '',
                    'gender'        => $data['data']['gender'] ?? '',
                    'address'       => $data['data']['residential_address'] ?? '',
                    'photo'         => '',
                ]];
            }
            return ['success' => false, 'message' => $data['message'] ?? 'BVN verification failed'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Service unavailable'];
        }
    }

    public function verifyDriversLicense(string $number, string $dob): array
    {
        try {
            $res  = Http::withHeaders($this->headers())
                ->post("{$this->baseUrl}/v2/lookup/drivers-license", [
                    'license_number' => $number,
                    'date_of_birth'  => $dob,
                ]);
            $data = $res->json();
            if ($res->successful() && isset($data['data'])) {
                return ['success' => true, 'data' => [
                    'full_name'     => $data['data']['full_name'] ?? '',
                    'date_of_birth' => $dob,
                    'photo'         => $data['data']['photo'] ?? '',
                    'address'       => $data['data']['address'] ?? '',
                ]];
            }
            return ['success' => false, 'message' => $data['message'] ?? 'License verification failed'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Service unavailable'];
        }
    }

    public function verifyPassport(string $number, string $lastName, string $dob): array
    {
        try {
            $res  = Http::withHeaders($this->headers())
                ->post("{$this->baseUrl}/v2/lookup/passport", [
                    'passport_number' => $number,
                    'last_name'       => $lastName,
                    'date_of_birth'   => $dob,
                ]);
            $data = $res->json();
            if ($res->successful() && isset($data['data'])) {
                return ['success' => true, 'data' => [
                    'full_name'     => $data['data']['full_name'] ?? '',
                    'date_of_birth' => $dob,
                    'gender'        => $data['data']['gender'] ?? '',
                    'photo'         => $data['data']['photo'] ?? '',
                ]];
            }
            return ['success' => false, 'message' => $data['message'] ?? 'Passport verification failed'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Service unavailable'];
        }
    }

    public function verifyCac(string $cacNumber): array
    {
        try {
            $res  = Http::withHeaders($this->headers())->timeout(30)
                ->post("{$this->baseUrl}/v2/lookup/cac/basic", ['rc_number' => $cacNumber]);
            $data = $res->json();
            if ($res->successful() && isset($data['data'])) {
                return ['success' => true, 'data' => [
                    'company_name'         => $data['data']['company_name'] ?? '',
                    'rc_number'            => $cacNumber,
                    'status'               => $data['data']['status'] ?? 'ACTIVE',
                    'company_type'         => $data['data']['company_type'] ?? '',
                    'date_of_registration' => $data['data']['date_of_registration'] ?? '',
                    'address'              => $data['data']['address'] ?? '',
                    'directors'            => $data['data']['directors'] ?? [],
                ]];
            }
            return ['success' => false, 'message' => $data['message'] ?? 'CAC lookup failed'];
        } catch (\Exception $e) {
            Log::error('Mono CAC: ' . $e->getMessage());
            return ['success' => false, 'message' => 'CAC service unavailable'];
        }
    }
}
