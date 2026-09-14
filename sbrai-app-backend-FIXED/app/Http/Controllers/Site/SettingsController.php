<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;

class SettingsController extends Controller
{
    public function show()
    {
        return view('site.settings');
    }
}
