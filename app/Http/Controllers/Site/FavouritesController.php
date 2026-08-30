<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;

class FavouritesController extends Controller
{
    public function show()
    {
        return view('site.favourites');
    }
}
