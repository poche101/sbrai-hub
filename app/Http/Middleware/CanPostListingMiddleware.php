<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

// Blocks vendors from posting if not KYC verified or not subscribed
class CanPostListingMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        if ($user->kyc_status !== 'verified') {
            return response()->json([
                'success' => false,
                'message' => 'KYC verification required to post listings.',
                'code'    => 'KYC_REQUIRED',
                'action'  => 'complete_kyc',
            ], 403);
        }

        $hasActiveSub = $user->subscriptions()
            ->where('status', 'active')
            ->where('end_date', '>', now())
            ->exists();

        if (!$hasActiveSub) {
            return response()->json([
                'success' => false,
                'message' => 'Active subscription required. Subscribe for ₦20,000/year or 10 Espees/year.',
                'code'    => 'SUBSCRIPTION_REQUIRED',
                'action'  => 'subscribe',
            ], 403);
        }

        return $next($request);
    }
}
