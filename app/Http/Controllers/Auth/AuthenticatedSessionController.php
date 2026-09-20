<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::validate($credentials)) {
            throw ValidationException::withMessages([
                'email' => 'Ces identifiants ne correspondent à aucun compte.',
            ]);
        }

        $user = \App\Models\User::where('email', $credentials['email'])->first();

        // Not verified yet — send them through the 2FA / email-verification flow.
        if (is_null($user->email_verified_at)) {
            $request->session()->put('2fa_user_id', $user->id);
            $request->session()->put('2fa_remember', $request->boolean('remember'));

            return redirect()->route('2fa');
        }

        // Already verified — log in directly, no 2FA needed.
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        // Check if user already has a role (existing user)
        if ($user->hasAnyRole(['producteur', 'transformateur', 'distributeur', 'consommateur'])) {
            return redirect()->intended(route('dashboard'));
        }

        // New user without role - redirect to role selection
        return redirect()->route('role-selection.create');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}