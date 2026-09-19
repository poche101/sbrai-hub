<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;   // ← swap Blade for View
use App\Models\Category;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {

        // Registers the "mail" view namespace so <x-mail::layout> in
        // resources/views/emails/*.blade.php resolves to
        // resources/views/components/mail/layout.blade.php. Laravel's
        // Blade component compiler hardcodes a bypass for the "mail::"
        // prefix that skips anonymousComponentNamespace() entirely and
        // expects a real View namespace to already exist — which only
        // happens automatically if a Mailable calls ->markdown(), which
        // none of ours do (they all use ->view()).
        View::addNamespace('mail', resource_path('views/components/mail'));
    }
}
