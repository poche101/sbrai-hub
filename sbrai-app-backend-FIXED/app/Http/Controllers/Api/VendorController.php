<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Listing;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;


// ─── Vendor Controller ────────────────────────────────────────────────
class VendorController extends Controller
{
    public function myListings(Request $request): JsonResponse
    {
        $listings = $request->user()->listings()
            ->orderByDesc('created_at')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data'    => $listings->map(fn($l) => [
                'id'          => $l->id,
                'title'       => $l->title,
                'category'    => $l->category,
                'price'       => (float) $l->price,
                'price_unit'  => $l->price_unit,
                'status'      => $l->status,
                'view_count'  => $l->view_count,
                'image_urls'  => $l->image_urls ?? [],
                'created_at'  => $l->created_at->toISOString(),
            ]),
            'meta' => ['total' => $listings->total()],
        ]);
    }

    public function dashboard(Request $request): JsonResponse
    {
        $user = $request->user();
        return response()->json([
            'success'    => true,
            'stats'      => [
                'active_listings' => $user->listings()->where('status', 'active')->count(),
                'total_views'     => $user->listings()->sum('view_count'),
                'total_chats'     => Chat::where('vendor_id', $user->id)->count(),
                'total_revenue'   => 0, // Implement when payments go live
            ],
            'activities' => [], // Recent activity log
        ]);
    }

    public function show(string $id): JsonResponse
    {
        $vendor = User::where('role', 'vendor')->findOrFail($id);
        return response()->json([
            'success' => true,
            'vendor'  => [
                'id'               => $vendor->id,
                'full_name'        => $vendor->full_name,
                'business_name'    => $vendor->business_name,
                'business_address' => $vendor->business_address,
                'avatar_url'       => $vendor->avatar_url,
                'is_verified'      => $vendor->is_verified,
                'rating'           => round($vendor->rating ?? 0, 1),
                'review_count'     => $vendor->review_count ?? 0,
                'joined'           => $vendor->created_at->format('M Y'),
                'listing_count'    => $vendor->listings()->where('status', 'active')->count(),
            ],
        ]);
    }

    public function listings(string $id): JsonResponse
    {
        $listings = Listing::where('vendor_id', $id)
            ->where('status', 'active')
            ->latest()
            ->paginate(20);
        return response()->json(['success' => true, 'data' => $listings->items()]);
    }

    public function reviews(string $id): JsonResponse
    {
        return response()->json(['success' => true, 'data' => []]);
    }

    public function addReview(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:500',
        ]);
        // TODO: implement reviews table
        return response()->json(['success' => true, 'message' => 'Review submitted']);
    }

    public function analytics(Request $request): JsonResponse
    {
        $user = $request->user();
        return response()->json([
            'success' => true,
            'data'    => [
                'views_by_day'    => [],
                'chats_by_day'    => [],
                'top_listings'    => [],
                'total_views'     => $user->listings()->sum('view_count'),
                'active_listings' => $user->listings()->where('status', 'active')->count(),
            ],
        ]);
    }
}
