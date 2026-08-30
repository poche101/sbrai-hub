<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route; // 💡 Added this import
use Illuminate\Support\Facades\Blade;
use App\Models\Category;              // 💡 Added this import

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 💡 Explicitly bind the route parameter {id} to the Category model
        Route::model('id', Category::class);

        // Registers the "mail" component namespace so <x-mail::layout> in
        // resources/views/emails/*.blade.php resolves to
        // resources/views/components/mail/layout.blade.php. This was
        // never registered anywhere in the project — every email using
        // <x-mail::layout> (otp, confirm-account, reset-password,
        // subscription-confirmed) would throw "No hint path defined for
        // [mail]" the moment it was actually sent, since dropping a file
        // in components/mail/ alone only gives dot-notation <x-mail.layout>,
        // not the :: namespace syntax those views actually use.
        Blade::anonymousComponentNamespace('components.mail', 'mail');
    }
}
