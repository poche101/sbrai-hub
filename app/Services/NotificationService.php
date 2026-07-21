<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class NotificationService
{
    private static string $fcmUrl = 'https://fcm.googleapis.com/fcm/send';

    public static function sendPush(User $user, string $title, string $body, array $data = []): bool
    {
        if (empty($user->fcm_token)) {
            return false;
        }

        try {
            Http::withHeaders([
                'Authorization' => 'key=' . config('services.firebase.server_key'),
                'Content-Type'  => 'application/json',
            ])->post(self::$fcmUrl, [
                'to'           => $user->fcm_token,
                'notification' => [
                    'title' => $title,
                    'body'  => $body,
                    'sound' => 'default',
                    'badge' => '1',
                    'icon'  => 'ic_notification',
                    'color' => '#E8622A',
                ],
                'data' => array_merge($data, [
                    'title'        => $title,
                    'body'         => $body,
                    'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                ]),
                'priority' => 'high',
            ]);

            $user->notifications()->create([
                'id'   => (string) Str::uuid(),
                'type' => 'App\\Notifications\\SbraiNotification',
                'data' => array_merge(['title' => $title, 'body' => $body], $data),
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('FCM error: ' . $e->getMessage());
            return false;
        }
    }

    public static function sendWelcome(User $user): void
    {
        self::sendPush(
            $user,
            'Welcome to Sbrai! 🎉',
            $user->role === 'vendor'
                ? 'Complete KYC and subscribe to start posting listings.'
                : 'Browse building materials and services near you.',
            ['type' => 'welcome']
        );
    }

    public static function sendNewMessage(User $recipient, string $sender, string $msg, string $chatId): void
    {
        self::sendPush($recipient, "New message from {$sender}", $msg, ['type' => 'chat', 'chat_id' => $chatId]);
    }

    public static function sendSubscriptionReminder(User $vendor, int $daysLeft): void
    {
        self::sendPush(
            $vendor,
            'Subscription expiring soon ⚠️',
            "Your subscription expires in {$daysLeft} day(s). Renew to keep posting.",
            ['type' => 'subscription_reminder']
        );
    }
}
