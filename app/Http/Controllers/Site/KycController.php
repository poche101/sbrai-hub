<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;

class KycController extends Controller
{
    public function show()
    {
        return view('site.kyc');
    }
}
