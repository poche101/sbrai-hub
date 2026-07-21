<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

// ─── Favorite Controller ──────────────────────────────────────────────
class FavoriteController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $favorites = $request->user()
            ->favorites()
            ->with('listing.vendor:id,full_name,business_name,is_verified,rating')
            ->get()
            ->pluck('listing')
            ->filter();

        return response()->json([
            'success' => true,
            'data'    => $favorites->map(fn($l) => [
                'id'                   => $l->id,
                'vendor_id'            => $l->vendor_id,
                'vendor_name'          => $l->vendor->full_name ?? '',
                'vendor_business_name' => $l->vendor->business_name ?? null,
                'vendor_verified'      => $l->vendor->is_verified ?? false,
                'vendor_rating'        => round($l->vendor->rating ?? 0, 1),
                'title'                => $l->title,
                'description'          => $l->description,
                'price'                => (float) $l->price,
                'price_unit'           => $l->price_unit,
                'category'             => $l->category,
                'type'                 => $l->type,
                'status'               => $l->status,
                'location'             => $l->location,
                'state'                => $l->state,
                'image_urls'           => $l->image_urls ?? [],
                'view_count'           => $l->view_count ?? 0,
                'is_favorited'         => true,
                'created_at'           => $l->created_at->toISOString(),
            ]),
        ]);
    }

    public function toggle(Request $request): JsonResponse
    {
        $request->validate(['listing_id' => 'required|exists:listings,id']);
        $user      = $request->user();
        $favorite  = $user->favorites()->where('listing_id', $request->listing_id)->first();

        if ($favorite) {
            $favorite->delete();
            return response()->json(['success' => true, 'favorited' => false]);
        }

        $user->favorites()->create(['listing_id' => $request->listing_id]);
        return response()->json(['success' => true, 'favorited' => true]);
    }
}

