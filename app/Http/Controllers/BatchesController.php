<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class BatchesController extends Controller
{
    public function __invoke()
    {
        return view('pages.batches', [
            'user' => Auth::user(),
        ]);
    }
}
