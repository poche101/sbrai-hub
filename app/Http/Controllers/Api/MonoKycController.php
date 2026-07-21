<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\MonoKycService;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

// ─── Mono KYC Controller (Enhanced) ──────────────────────────────────
class MonoKycController extends Controller
{
    public function __construct(private MonoKycService $mono) {}

    // ─── NIN Verification via Mono ────────────────────────────────────
    public function verifyNin(Request $request): JsonResponse
    {
        $request->validate(['nin' => 'required|string|digits:11']);
        $user   = $request->user();
        $result = $this->mono->verifyNin($request->nin);

        if ($result['success']) {
            $user->update([
                'identity_type'        => 'nin',
                'identity_number'      => $request->nin,
                'identity_verified_at' => now(),
            ]);
            $this->updateKycStatus($user);
        }

        return response()->json([
            'success' => $result['success'],
            'message' => $result['success'] ? 'NIN verified successfully' : ($result['message'] ?? 'Verification failed'),
            'data'    => $result['success'] ? $result['data'] : null,
        ], $result['success'] ? 200 : 422);
    }

    // ─── BVN Verification via Mono ────────────────────────────────────
    public function verifyBvn(Request $request): JsonResponse
    {
        $request->validate(['bvn' => 'required|string|digits:11']);
        $user   = $request->user();
        $result = $this->mono->verifyBvn($request->bvn);

        if ($result['success']) {
            $user->update([
                'identity_type'        => 'bvn',
                'identity_number'      => $request->bvn,
                'identity_verified_at' => now(),
            ]);
            $this->updateKycStatus($user);
        }

        return response()->json([
            'success' => $result['success'],
            'message' => $result['success'] ? 'BVN verified successfully' : ($result['message'] ?? 'Verification failed'),
            'data'    => $result['success'] ? $result['data'] : null,
        ], $result['success'] ? 200 : 422);
    }

    // ─── Driver's License via Mono ────────────────────────────────────
    public function verifyDriversLicense(Request $request): JsonResponse
    {
        $request->validate([
            'license_number' => 'required|string',
            'date_of_birth'  => 'required|date',
        ]);

        $result = $this->mono->verifyDriversLicense(
            $request->license_number,
            $request->date_of_birth
        );

        if ($result['success']) {
            $request->user()->update([
                'identity_type'        => 'drivers_license',
                'identity_number'      => $request->license_number,
                'identity_verified_at' => now(),
            ]);
            $this->updateKycStatus($request->user());
        }

        return response()->json([
            'success' => $result['success'],
            'message' => $result['success'] ? "Driver's license verified" : ($result['message'] ?? 'Verification failed'),
            'data'    => $result['success'] ? $result['data'] : null,
        ], $result['success'] ? 200 : 422);
    }

    // ─── Passport via Mono ────────────────────────────────────────────
    public function verifyPassport(Request $request): JsonResponse
    {
        $request->validate([
            'passport_number' => 'required|string',
            'last_name'       => 'required|string',
            'date_of_birth'   => 'required|date',
        ]);

        $result = $this->mono->verifyPassport(
            $request->passport_number,
            $request->last_name,
            $request->date_of_birth
        );

        if ($result['success']) {
            $request->user()->update([
                'identity_type'        => 'passport',
                'identity_number'      => $request->passport_number,
                'identity_verified_at' => now(),
            ]);
            $this->updateKycStatus($request->user());
        }

        return response()->json([
            'success' => $result['success'],
            'message' => $result['success'] ? 'Passport verified' : ($result['message'] ?? 'Verification failed'),
            'data'    => $result['success'] ? $result['data'] : null,
        ], $result['success'] ? 200 : 422);
    }

    // ─── CAC Business Verification via Mono ──────────────────────────
    public function verifyCac(Request $request): JsonResponse
    {
        $request->validate(['cac_number' => 'required|string|max:20']);
        $result = $this->mono->verifyCac($request->cac_number);

        if ($result['success']) {
            // Give vendor the verified badge
            $request->user()->update([
                'cac_number'  => $request->cac_number,
                'is_verified' => true,
            ]);
        }

        return response()->json([
            'success' => $result['success'],
            'message' => $result['success']
                ? 'Business verified: ' . ($result['data']['company_name'] ?? '')
                : ($result['message'] ?? 'CAC verification failed'),
            'data'    => $result['success'] ? $result['data'] : null,
        ], $result['success'] ? 200 : 422);
    }

    // ─── KYC Status ──────────────────────────────────────────────────
    public function status(Request $request): JsonResponse
    {
        $user = $request->user();
        return response()->json([
            'success'          => true,
            'status'           => $user->kyc_status,
            'email_verified'   => $user->email_verified_at !== null,
            'phone_verified'   => $user->phone_verified_at !== null,
            'identity_verified'=> $user->identity_verified_at !== null,
            'is_verified'      => $user->is_verified,
            'progress'         => $this->calcProgress($user),
            'rejection_reason' => $user->kyc_rejection_reason,
        ]);
    }

    // ─── Email OTP (Termii/Mailgun) ───────────────────────────────────
    public function sendEmailOtp(Request $request): JsonResponse
    {
        $user = $request->user();
        $otp  = OtpService::generate($user->email, 'email_verify');
        OtpService::sendEmail($user->email, $otp, $user->full_name);
        return response()->json(['success' => true, 'message' => 'OTP sent to ' . $user->email]);
    }

    public function verifyEmail(Request $request): JsonResponse
    {
        $request->validate(['otp' => 'required|string|digits:6']);
        $user = $request->user();
        if (!OtpService::verify($user->email, $request->otp, 'email_verify')) {
            return response()->json(['success' => false, 'message' => 'Invalid or expired OTP'], 422);
        }
        $user->update(['email_verified_at' => now()]);
        $this->updateKycStatus($user);
        return response()->json(['success' => true, 'message' => 'Email verified successfully']);
    }

    // ─── Phone OTP (Termii SMS) ───────────────────────────────────────
    public function sendPhoneOtp(Request $request): JsonResponse
    {
        $user = $request->user();
        $otp  = OtpService::generate($user->phone, 'phone_verify');
        OtpService::sendSms($user->phone, $otp);
        return response()->json(['success' => true, 'message' => 'OTP sent to ' . $user->phone]);
    }

    public function verifyPhone(Request $request): JsonResponse
    {
        $request->validate(['otp' => 'required|string|digits:6']);
        $user = $request->user();
        if (!OtpService::verify($user->phone, $request->otp, 'phone_verify')) {
            return response()->json(['success' => false, 'message' => 'Invalid or expired OTP'], 422);
        }
        $user->update(['phone_verified_at' => now()]);
        $this->updateKycStatus($user);
        return response()->json(['success' => true, 'message' => 'Phone verified successfully']);
    }

    // ─── Upload KYC Docs ─────────────────────────────────────────────
    public function uploadDocuments(Request $request): JsonResponse
    {
        $request->validate(['images.*' => 'required|file|mimes:jpg,jpeg,png,pdf|max:10240']);
        $user = $request->user();
        $urls = $user->kyc_documents ?? [];
        foreach ($request->file('images', []) as $file) {
            $path   = $file->store("kyc/{$user->id}", 'private');
            $urls[] = $path;
        }
        $user->update(['kyc_documents' => $urls]);
        return response()->json(['success' => true, 'message' => 'Documents uploaded successfully']);
    }

    // ─── Helpers ─────────────────────────────────────────────────────
    private function updateKycStatus($user): void
    {
        $e = $user->email_verified_at    !== null;
        $p = $user->phone_verified_at    !== null;
        $i = $user->identity_verified_at !== null;

        if ($e && $p && $i) {
            $user->update(['kyc_status' => 'verified']);
        } elseif ($e || $p || $i) {
            if ($user->kyc_status === 'not_submitted') {
                $user->update(['kyc_status' => 'pending']);
            }
        }
    }

    private function calcProgress($user): int
    {
        $done = 0;
        if ($user->email_verified_at)    $done++;
        if ($user->phone_verified_at)    $done++;
        if ($user->identity_verified_at) $done++;
        return (int)(($done / 3) * 100);
    }
}
