<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class CertificationsController extends Controller
{
    public function __invoke()
    {
        return view('pages.certifications', [
            'user' => Auth::user(),
        ]);
    }
}
