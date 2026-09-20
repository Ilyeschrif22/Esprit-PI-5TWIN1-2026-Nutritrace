<?php

namespace App\Http\Controllers;

use App\Services\JwtService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(JwtService $jwtService): View
    {
        // Pass the authenticated user (with their roles) so the navbar
        // profile dropdown shows real data instead of hardcoded values.
        $user = auth()->user()->load(['roles']);

        // Issue a token for the current user so the dashboard can consume
        // the protected User API endpoints (/api/me for the profile and
        // /api/logout for the logout action).
        $apiToken = $jwtService->generateToken($user);

        return view('dashboard', [
            'user' => $user,
            'apiToken' => $apiToken,
        ]);
    }
}
