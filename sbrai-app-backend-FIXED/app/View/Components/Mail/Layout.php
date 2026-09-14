<?php

namespace App\View\Components\Mail;

use Illuminate\View\Component;
use Illuminate\View\View;

class Layout extends Component
{
    public function render(): View
    {
        return view('layouts.email');
    }
}
