<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ListingController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\MonoKycController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\VendorController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\CallingController;
use App\Http\Controllers\Api\TranslationController;
use App\Http\Controllers\Api\SupportController;

/*
|--------------------------------------------------------------------------
| Sbrai Solutions API Routes
|--------------------------------------------------------------------------
| Served at https://sbraisolutions.com/api/v1/...
| The /api prefix is applied automatically by Laravel (via the `api:`
| param in bootstrap/app.php withRouting). The /v1 prefix is added
| explicitly here so the whole file's routes live under it.
*/

Route::prefix('v1')->group(function () {

// ── Public Routes (No Auth Required) ────────────────────────────────────
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login',    [AuthController::class, 'login']);
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('reset-password',  [AuthController::class, 'resetPassword']);
    Route::get('confirm-account/{token}', [AuthController::class, 'confirmAccount']);

    // OAuth
    Route::post('google',   [AuthController::class, 'googleAuth']);
    Route::post('facebook', [AuthController::class, 'facebookAuth']);
});

// Public categories (read-only, admin-managed)
Route::get('categories', [ListingController::class, 'categories']);

// Public listing browsing
Route::get('listings',              [ListingController::class, 'index']);
Route::get('listings/trending',     [ListingController::class, 'trending']);
Route::get('listings/recommended',  [ListingController::class, 'recommended']);
Route::get('listings/{id}',         [ListingController::class, 'show']);

// Public translation — deliberately outside auth:sanctum. Translating UI
// text isn't sensitive, and guests browsing before signing up are exactly
// who benefits most from a translated homepage/browse page.
Route::prefix('translate')->group(function () {
    Route::post('/',                    [TranslationController::class, 'translate']);
    Route::post('batch',                [TranslationController::class, 'translateBatch']);
    Route::post('listing/{listingId}',  [TranslationController::class, 'translateListing']);
});

// Public support chat — guests and signed-in users can both use it.
// SupportController::sendMessage checks $request->user() internally,
// so it works whether or not a Sanctum token is present.
Route::prefix('support')->group(function () {
    Route::post('conversations',              [SupportController::class, 'start']);
    Route::post('conversations/{id}/messages', [SupportController::class, 'sendMessage']);
    Route::get('conversations/{id}/messages',  [SupportController::class, 'history']);
});

// ── Protected Routes (Sanctum Auth Required) ─────────────────────────────
Route::middleware(['auth:sanctum', 'track.last_seen'])->group(function () {

    // ─── Auth
    Route::prefix('auth')->group(function () {
        Route::get('me',                    [AuthController::class, 'me']);
        Route::put('profile',               [AuthController::class, 'updateProfile']);
        Route::post('logout',               [AuthController::class, 'logout']);
        Route::post('change-password',      [AuthController::class, 'changePassword']);
        Route::post('fcm-token',            [AuthController::class, 'updateFcmToken']);
        Route::post('avatar',               [AuthController::class, 'uploadAvatar']);
        Route::post('resend-confirmation',  [AuthController::class, 'resendConfirmation']);
        Route::delete('account',            [AuthController::class, 'deleteAccount']);
    });

    // ─── KYC
    Route::prefix('kyc')->group(function () {
        Route::get('status',                [MonoKycController::class, 'status']);
        Route::post('email/send-otp',       [MonoKycController::class, 'sendEmailOtp']);
        Route::post('email/verify',         [MonoKycController::class, 'verifyEmail']);
        Route::post('phone/send-otp',       [MonoKycController::class, 'sendPhoneOtp']);
        Route::post('phone/verify',         [MonoKycController::class, 'verifyPhone']);
        Route::post('identity/nin',         [MonoKycController::class, 'verifyNin']);
        Route::post('identity/bvn',         [MonoKycController::class, 'verifyBvn']);
        Route::post('identity/drivers-license', [MonoKycController::class, 'verifyDriversLicense']);
        Route::post('identity/passport',    [MonoKycController::class, 'verifyPassport']);
        Route::post('business/cac',         [MonoKycController::class, 'verifyCac']);
        Route::post('identity/documents',   [MonoKycController::class, 'uploadDocuments']);
    });

    // ─── Listings (Vendor actions)
    Route::middleware('role:vendor,admin')->group(function () {
        Route::post('listings',             [ListingController::class, 'store'])
            ->middleware('can_post_listing');
        Route::put('listings/{id}',         [ListingController::class, 'update']);
        Route::delete('listings/{id}',      [ListingController::class, 'destroy']);
        Route::post('listings/{id}/images', [ListingController::class, 'uploadImages']);
        Route::patch('listings/{id}/status',[ListingController::class, 'updateStatus']);
        Route::get('vendor/listings',       [VendorController::class, 'myListings']);
        Route::get('vendor/dashboard',      [VendorController::class, 'dashboard']);
        Route::get('vendor/analytics',      [VendorController::class, 'analytics']);
    });

    // ─── Favorites (All users)
    Route::prefix('favorites')->group(function () {
        Route::get('/',                     [FavoriteController::class, 'index']);
        Route::post('toggle',               [FavoriteController::class, 'toggle']);
    });

    // ─── Chat / Messages
    Route::prefix('chats')->group(function () {
        Route::get('/',                         [ChatController::class, 'index']);
        Route::post('/',                        [ChatController::class, 'store']);
        Route::get('{chatId}/messages',         [ChatController::class, 'messages']);
        Route::post('{chatId}/messages',        [ChatController::class, 'sendMessage']);
        Route::post('{chatId}/read',            [ChatController::class, 'markRead']);
        Route::delete('{chatId}',               [ChatController::class, 'destroy']);
        Route::post('{chatId}/images',          [ChatController::class, 'uploadImage']);
    });

    // ─── Subscriptions & Payments
    Route::prefix('subscriptions')->middleware('role:vendor')->group(function () {
        Route::get('status',                    [SubscriptionController::class, 'status']);
        Route::get('transactions',              [SubscriptionController::class, 'transactions']);
        Route::get('voucher-balance',           [SubscriptionController::class, 'voucherBalance']);

        // Paystack (Hosted Storefront Integration)
        Route::get('paystack/checkout',         [SubscriptionController::class, 'getPaystackCheckout']);
        Route::post('paystack/verify',          [SubscriptionController::class, 'handlePaystackCallback']);

        // Espees Gateway
        Route::get('espees/checkout',           [SubscriptionController::class, 'espeesCheckout']);
        Route::post('espees/verify',            [SubscriptionController::class, 'espeesVerify']);
    });

    // ─── Notifications
    Route::prefix('notifications')->group(function () {
        Route::get('/',                         [NotificationController::class, 'index']);
        Route::post('{id}/read',                [NotificationController::class, 'markRead']);
        Route::post('read-all',                 [NotificationController::class, 'markAllRead']);
        Route::get('unread-count',              [NotificationController::class, 'unreadCount']);
    });

    // ─── Settings
    Route::prefix('settings')->group(function () {
        Route::get('/',                         [AuthController::class, 'getSettings']);
        Route::put('/',                         [AuthController::class, 'updateSettings']);
    });

    // ─── Search
    Route::get('search', [ListingController::class, 'search']);

    // ─── Vendor Profile (public)
    Route::get('vendors/{id}',              [VendorController::class, 'show']);
    Route::get('vendors/{id}/listings',     [VendorController::class, 'listings']);
    Route::get('vendors/{id}/reviews',      [VendorController::class, 'reviews']);
    Route::post('vendors/{id}/review',      [VendorController::class, 'addReview']);

    // ─── Calling (Agora)
    Route::prefix('calling')->group(function () {
        Route::post('token',      [CallingController::class, 'generateToken']);
        Route::post('initiate',   [CallingController::class, 'initiateCall']);
        Route::post('end',        [CallingController::class, 'endCall']);
    });
});

}); // end of v1 prefix group
