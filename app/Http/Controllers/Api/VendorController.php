<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Listing;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;


// ─── Vendor Controller ────────────────────────────────────────────────
class VendorController extends Controller
{
    private const ANALYTICS_DAYS = 14;

    public function myListings(Request $request): JsonResponse
    {
        $user = $request->user();

        $listings = $user->listings()
            ->orderByDesc('created_at')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data'    => $listings->map(fn($l) => [
                'id'                   => $l->id,
                'vendor_id'            => $user->id,
                'vendor_name'          => $user->full_name,
                'vendor_business_name' => $user->business_name,
                'vendor_verified'      => (bool) $user->is_verified,
                'title'                => $l->title,
                'description'          => $l->description,
                'category'             => $l->category,
                'type'                 => $l->type,
                'price'                => (float) $l->price,
                'price_unit'           => $l->price_unit,
                'status'               => $l->status,
                'location'             => $l->location,
                'state'                => $l->state,
                'view_count'           => (int) $l->view_count,
                'image_urls'           => $l->image_urls ?? [],
                'attributes'           => $l->attributes ?? [],
                'created_at'           => $l->created_at->toISOString(),
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
                'active_listings' => (int) $user->listings()->where('status', 'active')->count(),
                'total_views'     => (int) $user->listings()->sum('view_count'),
                'total_chats'     => (int) Chat::where('vendor_id', $user->id)->count(),
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
                // Gated by the vendor's own Settings > Privacy toggles.
                'phone'            => $vendor->privacyAllows('show_phone') ? $vendor->phone : null,
                'is_online'        => $vendor->is_online,
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

    // ─── Analytics ────────────────────────────────────────────────────
    // Last 14 days of views and new chats (zero-filled so charts have a
    // point for every day), plus the vendor's top listings by views.
    // Every number is cast so clients never receive "36" for 36.
    public function analytics(Request $request): JsonResponse
    {
        $user  = $request->user();
        $start = now()->subDays(self::ANALYTICS_DAYS - 1)->toDateString();

        $viewRows = [];
        if (Schema::hasTable('listing_views')) {
            $viewRows = DB::table('listing_views')
                ->join('listings', 'listings.id', '=', 'listing_views.listing_id')
                ->where('listings.vendor_id', $user->id)
                ->where('listing_views.date', '>=', $start)
                ->groupBy('listing_views.date')
                ->selectRaw('listing_views.date as d, SUM(listing_views.views) as n')
                ->pluck('n', 'd')
                ->all();
        }

        $chatRows = Chat::where('vendor_id', $user->id)
            ->where('created_at', '>=', $start . ' 00:00:00')
            ->selectRaw('DATE(created_at) as d, COUNT(*) as n')
            ->groupBy('d')
            ->pluck('n', 'd')
            ->all();

        $topListings = $user->listings()
            ->orderByDesc('view_count')
            ->limit(5)
            ->get()
            ->map(fn($l) => [
                'id'        => $l->id,
                'title'     => $l->title,
                'status'    => $l->status,
                'views'     => (int) $l->view_count,
                'favorites' => (int) $l->favorites()->count(),
            ])
            ->values();

        return response()->json([
            'success' => true,
            'data'    => [
                'views_by_day'    => $this->dailySeries($viewRows),
                'chats_by_day'    => $this->dailySeries($chatRows),
                'top_listings'    => $topListings,
                'total_views'     => (int) $user->listings()->sum('view_count'),
                'active_listings' => (int) $user->listings()->where('status', 'active')->count(),
            ],
        ]);
    }

    /** @return array<int, array{date: string, count: int}> */
    private function dailySeries(array $rowsByDate): array
    {
        $series = [];
        for ($i = self::ANALYTICS_DAYS - 1; $i >= 0; $i--) {
            $date     = now()->subDays($i)->toDateString();
            $series[] = ['date' => $date, 'count' => (int) ($rowsByDate[$date] ?? 0)];
        }
        return $series;
    }
}
