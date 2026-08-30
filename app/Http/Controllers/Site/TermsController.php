<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;

class TermsController extends Controller
{
    public function show()
    {
        return view('site.terms');
    }
}
