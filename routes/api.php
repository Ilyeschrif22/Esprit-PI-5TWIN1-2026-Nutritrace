<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Traceability\TraceabilityController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/login', [AuthController::class, 'login'])->name('api.login');

// Protected routes (require JWT authentication)
Route::middleware('jwt.auth')->group(function () {
    Route::get('/me', [AuthController::class, 'me'])->name('api.me');
    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');

    Route::get('/traceability', [TraceabilityController::class, 'index']);
    Route::get('/traceability/lots/{lot}', [TraceabilityController::class, 'show']);
    Route::get('/traceability/lots/{lot}/timeline', [TraceabilityController::class, 'timeline']);
    Route::get('/traceability/lots/{lot}/upstream', [TraceabilityController::class, 'upstream']);
    Route::get('/traceability/lots/{lot}/downstream', [TraceabilityController::class, 'downstream']);
    Route::get('/traceability/lots/{lot}/alerts', [TraceabilityController::class, 'alerts']);
    Route::get('/traceability/lots/{lot}/map', [TraceabilityController::class, 'map']);
    Route::post('/traceability/production', [TraceabilityController::class, 'storeProduction']);
    Route::post('/traceability/transformations', [TraceabilityController::class, 'storeTransformation']);
    Route::post('/traceability/cold-chain', [TraceabilityController::class, 'storeColdChain']);
    Route::post('/v1/shipments', [TraceabilityController::class, 'storeShipment']);
    Route::get('/public/traceability/{token}', [TraceabilityController::class, 'publicTrace']);
});
