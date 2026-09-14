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
            Route::middleware('web')
                ->prefix('admin')
                ->name('admin.')
                ->group(base_path('routes/admin.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Trust Nginx proxy headers so CSRF token and HTTPS session cookies pass through
        $middleware->trustProxies(at: '*');

        // Redirect unauthenticated web users to login
        $middleware->redirectTo('/admin/login');

        // Route aliases
        $middleware->alias([
            'role'             => \App\Http\Middleware\RoleMiddleware::class,
            'can_post_listing' => \App\Http\Middleware\CanPostListingMiddleware::class,
            'admin.only'       => \App\Http\Middleware\AdminOnlyMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->shouldRenderJsonWhen(function ($request, $e) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return true;
            }

            return false;
        });
    })->create();
