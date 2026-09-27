<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class StatsController extends Controller
{
    public function __invoke()
    {
        return view('pages.stats', [
            'user' => Auth::user(),
        ]);
    }
}
