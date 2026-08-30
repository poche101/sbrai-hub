<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Category;

class PostAdController extends Controller
{
    public function show()
    {
        $categories = Category::active()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('site.post-ad', [
            'categories' => $categories,
            'states'     => config('nigeria.states'),
        ]);
    }
}
