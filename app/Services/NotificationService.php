<?php

namespace App\Services;

use App\Models\Listing;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Exception\Messaging\NotFound;
use Kreait\Firebase\Exception\Messaging\InvalidArgument as MessagingInvalidArgument;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\AndroidConfig;
use Kreait\Firebase\Messaging\ApnsConfig;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;

// NOTE: this uses the FCM HTTP v1 API via kreait/firebase-php.
// The old fcm.googleapis.com/fcm/send "legacy" endpoint this service
// used to call was shut down by Google on 2024-07-22 — it no longer
// works with any key. v1 auths via a Firebase service-account JSON
// (config('services.firebase.credentials')) instead of a server key.
//
// Requires: composer require kreait/firebase-php
class NotificationService
{
    private static ?Messaging $messaging = null;

    private static function messaging(): Messaging
    {
        if (self::$messaging === null) {
            $credentials = config('services.firebase.credentials');

            if (empty($credentials) || !file_exists($credentials)) {
                throw new \RuntimeException(
                    'Firebase credentials not configured — set FIREBASE_CREDENTIALS in .env to the ' .
                    'path of your service-account JSON (see config/services.php).'
                );
            }

            self::$messaging = (new Factory)->withServiceAccount($credentials)->createMessaging();
        }

        return self::$messaging;
    }

    public static function sendPush(User $user, string $title, string $body, array $data = []): bool
    {
        if (empty($user->fcm_token)) {
            return false;
        }

        try {
            // v1's "data" payload only accepts string values.
            $stringData = array_map('strval', array_merge($data, [
                'title'        => $title,
                'body'         => $body,
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
            ]));

            $message = CloudMessage::withTarget('token', $user->fcm_token)
                ->withNotification(FirebaseNotification::create($title, $body))
                ->withData($stringData)
                ->withAndroidConfig(AndroidConfig::fromArray([
                    'priority'     => 'high',
                    'notification' => [
                        'sound' => 'default',
                        'color' => '#E8622A',
                        'icon'  => 'ic_notification',
                    ],
                ]))
                ->withApnsConfig(ApnsConfig::fromArray([
                    'headers' => ['apns-priority' => '10'],
                    'payload' => ['aps' => ['sound' => 'default', 'badge' => 1]],
                ]));

            self::messaging()->send($message);

            $user->notifications()->create([
                'id'   => (string) Str::uuid(),
                'type' => 'App\\Notifications\\SbraiNotification',
                'data' => array_merge(['title' => $title, 'body' => $body], $data),
            ]);

            return true;
        } catch (NotFound|MessagingInvalidArgument $e) {
            // Token is unregistered/invalid (app uninstalled, token
            // rotated, etc.) — clear it so we stop retrying a dead token.
            Log::warning("FCM token invalid for user {$user->id}, clearing it: " . $e->getMessage());
            $user->forceFill(['fcm_token' => null])->saveQuietly();
            return false;
        } catch (\Throwable $e) {
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

    public static function sendPriceDrop(User $buyer, Listing $listing, float $oldPrice): void
    {
        self::sendPush(
            $buyer,
            'Price drop! 📉',
            "{$listing->title} dropped from ₦" . number_format($oldPrice) . ' to ₦' . number_format($listing->price),
            ['type' => 'price_drop', 'listing_id' => $listing->id]
        );
    }

    public static function sendNewListingMatch(User $buyer, Listing $listing): void
    {
        self::sendPush(
            $buyer,
            'New listing near you 🆕',
            "{$listing->title} — ₦" . number_format($listing->price),
            ['type' => 'new_listing', 'listing_id' => $listing->id]
        );
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
