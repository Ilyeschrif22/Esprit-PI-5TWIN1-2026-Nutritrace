<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class MappingController extends Controller
{
    public function __invoke()
    {
        return view('pages.mapping', [
            'user' => Auth::user(),
        ]);
    }
}
