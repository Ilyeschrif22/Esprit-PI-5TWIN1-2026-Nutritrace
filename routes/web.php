<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PendingApprovalController;
use App\Http\Controllers\RoleSelectionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::get('role-selection', [RoleSelectionController::class, 'create'])->name('role-selection.create');
    Route::post('role-selection', [RoleSelectionController::class, 'store'])->name('role-selection.store');
    
    Route::get('pending-approval', PendingApprovalController::class)->name('pending-approval');
    
    Route::middleware('role.selected')->group(function () {
        Route::get('dashboard', DashboardController::class)->name('dashboard');
    });
    
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
