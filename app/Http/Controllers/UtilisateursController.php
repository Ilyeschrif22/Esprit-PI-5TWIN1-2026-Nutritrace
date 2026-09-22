<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class UtilisateursController extends Controller
{
    public function __invoke()
    {
        return view('pages.utilisateurs', [
            'user' => Auth::user(),
        ]);
    }
}
