<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

// Usage: ->middleware('role:vendor') or ->middleware('role:vendor,admin')
class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        $user = $request->user();

        if (!$user || !in_array($user->role, $roles)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. This action requires role: ' . implode(' or ', $roles),
            ], 403);
        }

        return $next($request);
    }
}
