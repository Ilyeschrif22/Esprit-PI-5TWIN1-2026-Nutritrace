<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DocumentsController extends Controller
{
    public function __invoke()
    {
        return view('pages.documents', [
            'user' => Auth::user(),
        ]);
    }
}
