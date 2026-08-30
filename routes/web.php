<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Site\HomeController;
use App\Http\Controllers\Site\BrowseController;
use App\Http\Controllers\Site\ListingPageController;
use App\Http\Controllers\Site\AuthPageController;
use App\Http\Controllers\Site\PostAdController;
use App\Http\Controllers\Site\MessagesController;
use App\Http\Controllers\Site\KycController;
use App\Http\Controllers\Site\PricingController;
use App\Http\Controllers\Site\SettingsController;
use App\Http\Controllers\Site\ProfileController;
use App\Http\Controllers\Site\FavouritesController;
use App\Http\Controllers\Site\TermsController;
use App\Http\Controllers\Site\PrivacyController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Public-facing web routes (non-API). The admin panel lives in
| routes/admin.php and is registered separately in bootstrap/app.php
| with the 'admin' prefix and 'admin.' route name prefix.
|
| These render server-side Blade shells (per the PRD's target web
| architecture) that call the existing /api/v1/... endpoints client-side
| for anything interactive (auth, favorites, posting, filtering).
*/

Route::get('/',            [HomeController::class, 'index'])->name('home');
Route::get('/browse',      [BrowseController::class, 'index'])->name('browse');
Route::get('/listing/{id}', [ListingPageController::class, 'show'])->name('listing.show');
Route::get('/auth',        [AuthPageController::class, 'show'])->name('site.auth');
Route::get('/forgot-password', [AuthPageController::class, 'showForgotPassword'])->name('password.request');
Route::get('/post-ad',     [PostAdController::class, 'show'])->name('post-ad');
Route::get('/messages',    [MessagesController::class, 'index'])->name('messages');
Route::get('/kyc',         [KycController::class, 'show'])->name('kyc');
Route::get('/pricing',     [PricingController::class, 'show'])->name('pricing');
Route::get('/settings',    [SettingsController::class, 'show'])->name('settings');
Route::get('/profile',     [ProfileController::class, 'show'])->name('profile');
Route::get('/favourites',  [FavouritesController::class, 'show'])->name('favourites');
Route::get('/terms',       [TermsController::class, 'show'])->name('terms');
Route::get('/privacy',     [PrivacyController::class, 'show'])->name('privacy');

// Admin login now lives only at /admin/login (routes/admin.php).

// ─── Password Reset (HTML form, opened from email links) ───────────────
// These two routes deliberately live here (under the `web` middleware
// group with sessions + CSRF) rather than in routes/api.php, because the
// Blade form needs CSRF protection and flash messages. They are still
// reachable at the same /api/v1/auth/... path the email link points to.
Route::prefix('api/v1/auth')->group(function () {
    Route::get('reset-password-form',  [AuthController::class, 'showResetForm']);
    Route::post('reset-password-form', [AuthController::class, 'handleResetForm']);
});

// Inside routes/web.php
// Account confirmation links from emails land on the API controller
// directly (see routes/api.php: GET /api/v1/auth/confirm-account/{token})
