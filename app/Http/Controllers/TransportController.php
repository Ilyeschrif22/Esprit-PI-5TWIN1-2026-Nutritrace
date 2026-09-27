<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class TransportController extends Controller
{
    public function __invoke()
    {
        return view('pages.transport', [
            'user' => Auth::user(),
        ]);
    }
}
