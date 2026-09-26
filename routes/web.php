<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BatchesController;
use App\Http\Controllers\CertificationsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentsController;
use App\Http\Controllers\EnvironmentalImpactController;
use App\Http\Controllers\MappingController;
use App\Http\Controllers\PendingApprovalController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\RoleSelectionController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\TraceabilityController;
use App\Http\Controllers\TransportController;
use App\Http\Controllers\UtilisateursController;
use App\Models\User;
use App\Services\TwoFactorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/** redirect to landing page */
Route::get('/', function () {
    return view('pages.landing');
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
    Route::get('role-selection', [RoleSelectionController::class, 'create'])->name('role-selection.create');
    Route::post('role-selection', [RoleSelectionController::class, 'store'])->name('role-selection.store');

    Route::get('pending-approval', PendingApprovalController::class)->name('pending-approval');

    Route::middleware('role.selected')->group(function () {
        Route::get('dashboard', DashboardController::class)->name('dashboard');
        /**profile route */
        Route::get('profile', function () {
            $user = auth()->user()->load('roles');

            return view('pages.profile', compact('user'));
        })->name('profile');

        Route::get('products', ProductsController::class)->name('products');
        Route::get('batches', BatchesController::class)->name('batches');
        Route::get('utilisateurs', UtilisateursController::class)->name('utilisateurs');
        Route::get('traceability', TraceabilityController::class)->name('traceability');
        Route::get('transport', TransportController::class)->name('transport');
        Route::get('certifications', CertificationsController::class)->name('certifications');
        Route::get('documents', DocumentsController::class)->name('documents');
        Route::get('env-impact', EnvironmentalImpactController::class)->name('env-impact');
        Route::get('mapping', MappingController::class)->name('mapping');
        Route::get('stats', StatsController::class)->name('stats');
    });

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});


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