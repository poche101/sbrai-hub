<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Listing;

class ListingPageController extends Controller
{
    public function show(string $id)
    {
        $listing = Listing::with('vendor')->findOrFail($id);
        $listing->increment('view_count');

        // Favorite state depends on the Sanctum bearer token, which lives
        // in the browser's localStorage rather than a server-readable
        // cookie/session — so it can't be resolved here. The page script
        // fills in the favorite star client-side via GET /api/v1/favorites
        // once it knows whether a token is present.
        return view('site.listing-show', [
            'listing' => $listing,
        ]);
    }
}
