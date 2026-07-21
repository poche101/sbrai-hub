<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Public-facing web routes (non-API). The admin panel lives in
| routes/admin.php and is registered separately in bootstrap/app.php
| with the 'admin' prefix and 'admin.' route name prefix.
*/

Route::get('/', function () {
    // Redirect localhost:8000 straight to your admin login panel
    return redirect()->route('admin.login');
});

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
