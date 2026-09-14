<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiThrottleMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Let Laravel's built-in throttle handle rate limiting
        return $next($request);
    }
}
