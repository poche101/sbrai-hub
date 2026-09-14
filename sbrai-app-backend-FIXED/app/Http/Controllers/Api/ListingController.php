<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class ListingController extends Controller
{
    // ─── Public: Index ────────────────────────────────────────────────
    public function index(Request $request): JsonResponse
    {
        $query = Listing::with('vendor:id,full_name,business_name,is_verified,rating')
            ->where('status', 'active');

        // Filters
        if ($request->filled('category'))  $query->where('category', $request->category);
        if ($request->filled('state'))     $query->where('state', $request->state);
        if ($request->filled('type'))      $query->where('type', $request->type);
        if ($request->filled('min_price')) $query->where('price', '>=', $request->min_price);
        if ($request->filled('max_price')) $query->where('price', '<=', $request->max_price);

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($qb) use ($q) {
                $qb->where('title', 'LIKE', "%$q%")
                   ->orWhere('description', 'LIKE', "%$q%")
                   ->orWhere('category', 'LIKE', "%$q%");
            });
        }

        // Sorting
        match ($request->sort ?? 'recent') {
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'popular'    => $query->orderBy('view_count', 'desc'),
            default      => $query->latest(),
        };

        $listings = $query->paginate($request->per_page ?? 20);

        // Mark favorites for authenticated users
        $favoriteIds = [];
        if (auth('sanctum')->check()) {
            $favoriteIds = auth('sanctum')->user()
                ->favorites()->pluck('listing_id')->toArray();
        }

        return response()->json([
            'success' => true,
            'data'    => $listings->map(fn($l) => $this->formatListing($l, $favoriteIds)),
            'meta'    => [
                'current_page' => $listings->currentPage(),
                'last_page'    => $listings->lastPage(),
                'total'        => $listings->total(),
            ],
        ]);
    }

    // ─── Public: Show ─────────────────────────────────────────────────
    public function show(string $id): JsonResponse
    {
        $listing = Listing::with('vendor')->findOrFail($id);
        $listing->increment('view_count');

        $isFavorited = false;
        if (auth('sanctum')->check()) {
            $isFavorited = auth('sanctum')->user()
                ->favorites()->where('listing_id', $id)->exists();
        }

        return response()->json([
            'success' => true,
            'listing' => $this->formatListing($listing, $isFavorited ? [$id] : []),
        ]);
    }

    // ─── Vendor: Store ────────────────────────────────────────────────
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'price'       => 'required|numeric|min:0',
            'price_unit'  => 'required|string|max:100',
            'category'    => 'required|string',
            'type'        => 'required|in:product,service,property',
            'location'    => 'required|string|max:255',
            'state'       => 'required|string|max:100',
        ]);

        $listing = $request->user()->listings()->create([
            'title'       => $request->title,
            'description' => $request->description,
            'price'       => $request->price,
            'price_unit'  => $request->price_unit,
            'category'    => $request->category,
            'type'        => $request->type,
            'location'    => $request->location,
            'state'       => $request->state,
            'status'      => 'active',
            'image_urls'  => [],
            'attributes'  => $request->attributes ?? [],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Listing created successfully',
            'listing' => $this->formatListing($listing->load('vendor')),
        ], 201);
    }

    // ─── Vendor: Update ───────────────────────────────────────────────
    public function update(Request $request, string $id): JsonResponse
    {
        $listing = $request->user()->listings()->findOrFail($id);

        $request->validate([
            'title'       => 'sometimes|string|max:255',
            'description' => 'sometimes|string|max:2000',
            'price'       => 'sometimes|numeric|min:0',
            'price_unit'  => 'sometimes|string|max:100',
            'category'    => 'sometimes|string',
            'type'        => 'sometimes|in:product,service,property',
            'location'    => 'sometimes|string|max:255',
            'state'       => 'sometimes|string|max:100',
            'status'      => 'sometimes|in:active,draft,sold,removed',
        ]);

        $listing->update($request->only([
            'title', 'description', 'price', 'price_unit',
            'category', 'type', 'location', 'state', 'status',
        ]));

        return response()->json([
            'success' => true,
            'listing' => $this->formatListing($listing->load('vendor')),
        ]);
    }

    // ─── Vendor: Delete ───────────────────────────────────────────────
    public function destroy(Request $request, string $id): JsonResponse
    {
        $listing = $request->user()->listings()->findOrFail($id);

        // Delete images from storage
        foreach ($listing->image_urls ?? [] as $url) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $url));
        }

        $listing->delete();

        return response()->json(['success' => true, 'message' => 'Listing deleted']);
    }

    // ─── Upload Images ────────────────────────────────────────────────
    public function uploadImages(Request $request, string $id): JsonResponse
    {
        $request->validate(['images.*' => 'required|image|max:5120']);

        $listing = $request->user()->listings()->findOrFail($id);
        $urls = $listing->image_urls ?? [];

        foreach ($request->file('images', []) as $image) {
            $path = $image->store("listings/{$id}", 'public');
            $urls[] = '/storage/' . $path;
        }

        // Max 5 images per listing
        $urls = array_slice($urls, 0, 5);
        $listing->update(['image_urls' => $urls]);

        return response()->json([
            'success'    => true,
            'image_urls' => $urls,
        ]);
    }

    // ─── Update Status ────────────────────────────────────────────────
    public function updateStatus(Request $request, string $id): JsonResponse
    {
        $request->validate(['status' => 'required|in:active,draft,sold,removed']);
        $listing = $request->user()->listings()->findOrFail($id);
        $listing->update(['status' => $request->status]);
        return response()->json(['success' => true, 'status' => $request->status]);
    }

    // ─── Trending ─────────────────────────────────────────────────────
    public function trending(): JsonResponse
    {
        $listings = Listing::with('vendor')
            ->where('status', 'active')
            ->orderByDesc('view_count')
            ->limit(20)
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $listings->map(fn($l) => $this->formatListing($l)),
        ]);
    }

    // ─── Recommended ──────────────────────────────────────────────────
    public function recommended(Request $request): JsonResponse
    {
        // Simple recommendation: mix of recent and popular
        $listings = Listing::with('vendor')
            ->where('status', 'active')
            ->orderByDesc('created_at')
            ->limit(18)
            ->get();

        $favoriteIds = [];
        if (auth('sanctum')->check()) {
            $favoriteIds = auth('sanctum')->user()
                ->favorites()->pluck('listing_id')->toArray();
        }

        return response()->json([
            'success' => true,
            'data'    => $listings->map(fn($l) => $this->formatListing($l, $favoriteIds)),
        ]);
    }

    // ─── Search ───────────────────────────────────────────────────────
    public function search(Request $request): JsonResponse
    {
        return $this->index($request);
    }

    // ─── Categories (Admin-Managed) ───────────────────────────────────
    public function categories(): JsonResponse
    {
        $categories = \App\Models\Category::active()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->map(fn($c) => [
                'id'        => $c->id,
                'name'      => $c->name,
                'slug'      => $c->slug,
                'type'      => $c->listing_type,
                'icon'      => $c->icon,
                'image_url' => $c->image_url,
            ]);

        return response()->json(['success' => true, 'data' => $categories]);
    }

    // ─── Format Helper ────────────────────────────────────────────────
    private function formatListing(Listing $listing, array $favoriteIds = []): array
    {
        return [
            'id'                    => $listing->id,
            'vendor_id'             => $listing->vendor_id,
            'vendor_name'           => $listing->vendor->full_name ?? '',
            'vendor_business_name'  => $listing->vendor->business_name ?? null,
            'vendor_verified'       => $listing->vendor->is_verified ?? false,
            'vendor_rating'         => round($listing->vendor->rating ?? 0, 1),
            'title'                 => $listing->title,
            'description'           => $listing->description,
            'price'                 => (float) $listing->price,
            'price_unit'            => $listing->price_unit,
            'category'              => $listing->category,
            'type'                  => $listing->type,
            'status'                => $listing->status,
            'location'              => $listing->location,
            'state'                 => $listing->state,
            'image_urls'            => $listing->image_urls ?? [],
            'view_count'            => $listing->view_count ?? 0,
            'favorite_count'        => $listing->favorites()->count(),
            'is_favorited'          => in_array($listing->id, $favoriteIds),
            'created_at'            => $listing->created_at->toISOString(),
            'attributes'            => $listing->attributes ?? null,
        ];
    }
}
