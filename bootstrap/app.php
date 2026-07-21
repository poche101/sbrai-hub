<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            // ── Admin Panel (Blade + Tailwind) ──────────────────────────
            // Served at: https://sbraisolutions.com/api/admin/...
            // Mounted under /api so it lives on the same single domain
            // (sbraisolutions.com/api) as the rest of this backend.
            Route::middleware('web')
            ->prefix('admin')
                ->name('admin.')
                ->group(base_path('routes/admin.php'));
        },
    )
  ->withMiddleware(function (Middleware $middleware) {
        // Tell Laravel where to redirect unauthenticated web users
        $middleware->redirectTo('/admin/login'); // Or 'admin/login' depending on your URL prefix
          $middleware->prepend(\Illuminate\Http\Middleware\HandleCors::class);
        $middleware->alias([
            'role'             => \App\Http\Middleware\RoleMiddleware::class,
            'can_post_listing' => \App\Http\Middleware\CanPostListingMiddleware::class,
            'admin.only'       => \App\Http\Middleware\AdminOnlyMiddleware::class,
        ]);
    })
   ->withExceptions(function (Exceptions $exceptions) {
        // 💡 ONLY force JSON when the request is an API route or explicitly expects JSON
        $exceptions->shouldRenderJsonWhen(function ($request, $e) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return true;
            }

            return false; // Let standard web pages render HTML Blade layouts/errors
        });
    })->create();
