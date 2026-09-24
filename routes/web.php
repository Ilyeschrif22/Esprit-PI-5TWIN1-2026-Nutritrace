<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PendingApprovalController;
use App\Http\Controllers\RoleSelectionController;
use App\Http\Controllers\Traceability\TraceabilityController;
use App\Models\User;
use App\Services\TwoFactorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('reset-password/{token}', [PasswordResetLinkController::class, 'edit'])->name('password.reset');
    Route::post('reset-password', [PasswordResetLinkController::class, 'update'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::get('/role-selection', [RoleSelectionController::class, 'create'])->name('role-selection.create');
    Route::post('/role-selection', [RoleSelectionController::class, 'store'])->name('role-selection.store');

    Route::get('/traceability', [TraceabilityController::class, 'index'])->name('traceability.index');
    Route::get('/traceability/dashboard', [TraceabilityController::class, 'dashboard'])->name('traceability.dashboard');
    Route::get('/traceability/lots/{lot}', [TraceabilityController::class, 'show'])->name('traceability.show');
    Route::get('/traceability/lots/{lot}/timeline', [TraceabilityController::class, 'timeline'])->name('traceability.timeline');
    Route::get('/traceability/lots/{lot}/upstream', [TraceabilityController::class, 'upstream'])->name('traceability.upstream');
    Route::get('/traceability/lots/{lot}/downstream', [TraceabilityController::class, 'downstream'])->name('traceability.downstream');
    Route::get('/traceability/lots/{lot}/alerts', [TraceabilityController::class, 'alerts'])->name('traceability.alerts');
    Route::get('/traceability/lots/{lot}/map', [TraceabilityController::class, 'map'])->name('traceability.map');
    Route::post('/traceability/production', [TraceabilityController::class, 'storeProduction'])->name('traceability.production.store');
    Route::post('/traceability/transformations', [TraceabilityController::class, 'storeTransformation'])->name('traceability.transformations.store');
    Route::post('/traceability/cold-chain', [TraceabilityController::class, 'storeColdChain'])->name('traceability.coldchain.store');
    Route::post('/traceability/shipments', [TraceabilityController::class, 'storeShipment'])->name('traceability.shipments.store');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

Route::get('/trace/{token}', [TraceabilityController::class, 'publicTrace'])->name('traceability.public');

/** 2fa routes (guest — user isn't fully authenticated yet at this point) */
Route::middleware('guest')->group(function () {
    Route::get('/2fa', function (TwoFactorService $twoFactor) {
        $user = User::find(session('2fa_user_id'));

        if (! $user) {
            return redirect()->route('login');
        }

        $twoFactor->generateAndSend($user);

        return view('auth.2fa', [
            'maskedEmail' => $twoFactor->maskEmail($user->email),
        ]);
    })->name('2fa');

    Route::post('/2fa/verify', function (Request $request, TwoFactorService $twoFactor) {
        $request->validate(['code' => 'required|digits:6']);

        $user = User::find(session('2fa_user_id'));

        if (! $user) {
            return redirect()->route('login');
        }

        if (! $twoFactor->verify($request->code)) {
            return back()->withErrors(['code' => 'Code invalide ou expiré.']);
        }

        // Mark the account verified now that the code has been confirmed.
        if (is_null($user->email_verified_at)) {
            $user->forceFill(['email_verified_at' => now()])->save();
        }

        $remember = session('2fa_remember', false);
        session()->forget(['2fa_user_id', '2fa_remember']);

        Auth::login($user, $remember);
        $request->session()->regenerate();

        // Check if user already has a role (existing user)
        if ($user->hasAnyRole(['producteur', 'transformateur', 'distributeur', 'consommateur'])) {
            return redirect()->intended(route('dashboard'));
        }

        // New user without role - redirect to role selection
        return redirect()->route('role-selection.create');
    })->name('2fa.verify');

    Route::post('/2fa/resend', function (TwoFactorService $twoFactor) {
        $user = User::find(session('2fa_user_id'));

        if (! $user) {
            return response()->json(['status' => 'error'], 401);
        }

        $twoFactor->generateAndSend($user);

        return response()->json(['status' => 'sent']);
    })->name('2fa.resend');
});