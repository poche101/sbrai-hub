<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SupportController;

/*
|--------------------------------------------------------------------------
| Sbrai Admin Panel Routes
|--------------------------------------------------------------------------
| Loaded with prefix 'admin' and name prefix 'admin.' in RouteServiceProvider
| All views rendered with Laravel Blade + Tailwind CSS
*/

// ─── Guest (Admin Login) ───────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('login',  [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AdminAuthController::class, 'login'])->name('login.submit');
});

// ─── Authenticated Admin ───────────────────────────────────────────────
Route::middleware(['auth', 'admin.only'])->group(function () {
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.alt');

    Route::get('reports/revenue', [DashboardController::class, 'revenueReports'])->name('reports.revenue');

    Route::get('listings', [DashboardController::class, 'listings'])->name('listings.index');

    // KYC Management
    Route::get('kyc-requests', [DashboardController::class, 'kycRequests'])->name('kyc.index');
    Route::post('kyc/{user}/approve', [DashboardController::class, 'approveKyc'])->name('kyc.approve');
    Route::post('kyc/{id}/reject', [DashboardController::class, 'rejectKyc'])->name('kyc.reject');

    // User Management
    Route::get('users', [DashboardController::class, 'users'])->name('users.index');
    Route::post('users', [DashboardController::class, 'addUser'])->name('users.store');
    Route::delete('users/{id}', [DashboardController::class, 'deleteUser'])->name('users.destroy');

    // ─── Category Management (admin creates listing categories)
    Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('categories', [CategoryController::class, 'store'])->name('categories.store');

    Route::get('categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    Route::post('categories/{category}/toggle', [CategoryController::class, 'toggleActive'])->name('categories.toggle');
    Route::post('categories/reorder', [CategoryController::class, 'reorder'])->name('categories.reorder');

    // ─── Support Inbox (AI chat escalations) ────────────────────────
    Route::prefix('support')->name('support.')->group(function () {
        Route::get('/', [SupportController::class, 'index'])->name('index');
        Route::get('/{id}', [SupportController::class, 'show'])->name('show');
        Route::get('/{id}/messages', [SupportController::class, 'messages'])->name('messages');
        Route::get('/{id}/download', [SupportController::class, 'download'])->name('download');
        Route::post('/{id}/reply', [SupportController::class, 'reply'])->name('reply');
        Route::post('/{id}/resolve', [SupportController::class, 'resolve'])->name('resolve');
    });

    // 💡 ALWAYS put the generic show route at the absolute bottom of the list
    Route::get('categories/{category}', [CategoryController::class, 'show']);
});
