<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class TraceabilityController extends Controller
{
    public function __invoke()
    {
        return view('pages.traceability', [
            'user' => Auth::user(),
        ]);
    }
}
