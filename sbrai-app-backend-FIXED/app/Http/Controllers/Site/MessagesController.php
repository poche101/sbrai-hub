<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    public function index(Request $request)
    {
        return view('site.messages', [
            // Optional: /messages?chat=<id> opens straight into a thread
            // (used by the "Chat with vendor" redirect from a listing page).
            'openChatId' => $request->query('chat'),
        ]);
    }
}
