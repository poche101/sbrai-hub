<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class BrowseController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::active()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('site.browse', [
            'categories' => $categories,
            'states'     => config('nigeria.states'),
            // Initial filter values from the query string, so a shared/
            // bookmarked ?category=&state= URL pre-fills the form before
            // the client-side fetch runs.
            'initial' => [
                'q'        => $request->query('q', ''),
                'category' => $request->query('category', ''),
                'state'    => $request->query('state', ''),
                'type'     => $request->query('type', ''),
            ],
        ]);
    }
}
