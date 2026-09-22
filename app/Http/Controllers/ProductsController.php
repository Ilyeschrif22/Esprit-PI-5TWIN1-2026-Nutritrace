<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class ProductsController extends Controller
{
    public function __invoke()
    {
        return view('pages.products', [
            'user' => Auth::user(),
        ]);
    }
}
