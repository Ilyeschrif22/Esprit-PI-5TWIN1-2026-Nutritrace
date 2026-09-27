<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class EnvironmentalImpactController extends Controller
{
    public function __invoke()
    {
        return view('pages.env-impact', [
            'user' => Auth::user(),
        ]);
    }
}
