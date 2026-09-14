<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use App\Services\GoogleTranslateService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TranslationController extends Controller
{
    /**
     * Translate a single text.
     */
    public function translate(Request $request): JsonResponse
    {
        $request->validate([
            'text'   => 'required|string|max:5000',
            'target' => 'required|string|in:en,yo,ig,ha,fr',
            'source' => 'nullable|string',
        ]);

        $translated = GoogleTranslateService::translate(
            $request->text,
            $request->target,
            $request->source ?? 'en'
        );

        return response()->json([
            'success'    => true,
            'translated' => $translated,
            'source'     => $request->source ?? 'en',
            'target'     => $request->target,
        ]);
    }

    /**
     * Translate multiple texts in one request (cost efficient).
     */
    public function translateBatch(Request $request): JsonResponse
    {
        $request->validate([
            'texts'   => 'required|array|max:50',
            'texts.*' => 'required|string|max:1000',
            'target'  => 'required|string|in:en,yo,ig,ha,fr',
        ]);

        $results = GoogleTranslateService::translateBatch($request->texts, $request->target);

        return response()->json([
            'success'      => true,
            'translations' => $results,
            'target'       => $request->target,
        ]);
    }

    /**
     * Translate listing content (title + description).
     */
    public function translateListing(Request $request, string $listingId): JsonResponse
    {
        $request->validate(['target' => 'required|string|in:en,yo,ig,ha,fr']);

        $listing = Listing::findOrFail($listingId);
        $target  = $request->target;

        if ($target === 'en') {
            return response()->json([
                'success'     => true,
                'title'       => $listing->title,
                'description' => $listing->description,
            ]);
        }

        $results = GoogleTranslateService::translateBatch(
            [$listing->title, $listing->description],
            $target
        );

        return response()->json([
            'success'     => true,
            'title'       => $results[0] ?? $listing->title,
            'description' => $results[1] ?? $listing->description,
            'target'      => $target,
        ]);
    }
}
