<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OtpService
{
    public static function generate(string $identifier, string $type): string
    {
        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        Cache::put("otp:{$type}:{$identifier}", $otp, now()->addMinutes(10));
        return $otp;
    }

    public static function verify(string $identifier, string $otp, string $type): bool
    {
        $key    = "otp:{$type}:{$identifier}";
        $stored = Cache::get($key);
        if ($stored && $stored === $otp) {
            Cache::forget($key);
            return true;
        }
        return false;
    }

    public static function sendEmail(string $email, string $otp, string $name): void
    {
        Mail::send(
            'emails.otp',
            ['otp' => $otp, 'name' => $name],
            fn ($m) => $m->to($email)->subject('Your Sbrai Verification Code')
        );
    }

    public static function sendSms(string $phone, string $otp): void
    {
        try {
            Http::withHeaders(['Content-Type' => 'application/json'])
                ->post('https://api.ng.termii.com/api/sms/send', [
                    'to'      => $phone,
                    'from'    => 'Sbrai',
                    'sms'     => "Your Sbrai code: {$otp}. Valid 10 mins. Do not share.",
                    'type'    => 'plain',
                    'channel' => 'generic',
                    'api_key' => config('services.termii.api_key', ''),
                ]);
        } catch (\Exception $e) {
            Log::error('SMS OTP: ' . $e->getMessage());
        }
    }
}
