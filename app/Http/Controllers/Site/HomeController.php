<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Listing;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::active()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $trending = Listing::with('vendor:id,full_name,business_name,is_verified,rating')
            ->where('status', 'active')
            ->orderByDesc('view_count')
            ->limit(8)
            ->get();

        return view('site.home', [
            'categories' => $categories,
            'trending'   => $trending,
        ]);
    }
}
