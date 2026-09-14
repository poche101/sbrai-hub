<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Subscription;
use App\Services\NotificationService;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Scheduled Tasks
|--------------------------------------------------------------------------
*/

// Remind vendors 7 days before subscription expiry
Schedule::call(function () {
    Subscription::where('status', 'active')
        ->whereBetween('end_date', [now()->addDays(7), now()->addDays(8)])
        ->with('vendor')
        ->each(fn ($sub) => NotificationService::sendSubscriptionReminder($sub->vendor, 7));
})->daily();

// Remind vendors 1 day before subscription expiry
Schedule::call(function () {
    Subscription::where('status', 'active')
        ->whereBetween('end_date', [now()->addDay(), now()->addDays(2)])
        ->with('vendor')
        ->each(fn ($sub) => NotificationService::sendSubscriptionReminder($sub->vendor, 1));
})->daily();

// Mark expired subscriptions
Schedule::call(function () {
    Subscription::where('status', 'active')
        ->where('end_date', '<', now())
        ->update(['status' => 'expired']);
})->daily();
