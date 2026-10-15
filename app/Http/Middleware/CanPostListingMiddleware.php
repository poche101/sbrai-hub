<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

// Blocks vendors from posting unless they are KYC verified AND have an
// active (unexpired) subscription. The response `code` tells the client
// exactly which requirement is missing.
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
                'success'    => false,
                'message'    => 'KYC verification required to post listings.',
                'code'       => 'KYC_REQUIRED',
                'action'     => 'complete_kyc',
                'kyc_status' => $user->kyc_status,
            ], 403);
        }

        $hasActiveSub = $user->subscriptions()
            ->where('status', 'active')
            ->where('end_date', '>', now())
            ->exists();

        if (!$hasActiveSub) {
            $hadSubscription = $user->subscriptions()->exists();

            return response()->json([
                'success' => false,
                'message' => $hadSubscription
                    ? 'Your subscription has expired. Renew for ₦20,000/year or 10 Espees/year to keep posting.'
                    : 'Active subscription required. Subscribe for ₦20,000/year or 10 Espees/year.',
                'code'    => 'SUBSCRIPTION_REQUIRED',
                'action'  => 'subscribe',
            ], 403);
        }

        return $next($request);
    }
}
