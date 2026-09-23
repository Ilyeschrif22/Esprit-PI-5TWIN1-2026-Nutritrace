<?php

namespace App\Http\Controllers;

use App\Models\Lot;
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

        $lots = Lot::with('product')->latest()->limit(12)->get()->map(function (Lot $lot) {
            $origin = strtolower((string) ($lot->origin ?: $lot->location ?: 'Tunisie'));

            $productionPoints = [
                'nabeul' => [36.4511, 10.7322],
                'sfax' => [34.7406, 10.7604],
                'tunis' => [36.8065, 10.1815],
                'sousse' => [35.8254, 10.6367],
                'kairouan' => [35.6781, 10.0969],
                'gabes' => [33.8815, 10.0978],
            ];

            $coords = $productionPoints[$origin] ?? [36.8065, 10.1815];

            return [
                'lot_number' => $lot->lot_number,
                'product_name' => $lot->product?->name ?? 'Produit',
                'origin' => $lot->origin ?: $lot->location ?: 'Tunisie',
                'lat' => $coords[0],
                'lng' => $coords[1],
            ];
        });

        return view('dashboard', [
            'user' => $user,
            'apiToken' => $apiToken,
            'lots' => $lots,
        ]);
    }
}

