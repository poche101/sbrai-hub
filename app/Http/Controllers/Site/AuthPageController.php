<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthPageController extends Controller
{
    public function show(Request $request)
    {
        return view('site.auth', [
            // ?mode=register opens the create-account tab by default;
            // anything else (or nothing) opens the login tab.
            'mode'           => $request->query('mode', 'login') === 'register' ? 'register' : 'login',
            'googleClientId' => config('services.google.client_id'),
            'facebookAppId'  => config('services.facebook.app_id'),
        ]);
    }

    public function showForgotPassword()
{
    return view('site.forgot-password', [
        'googleClientId' => config('services.google.client_id'),
    ]);
}
}
