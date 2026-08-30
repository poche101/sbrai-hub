<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;

class PricingController extends Controller
{
    public function show()
    {
        return view('site.pricing');
    }
}
