<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;

class PrivacyController extends Controller
{
    public function show()
    {
        return view('site.privacy');
    }
}
