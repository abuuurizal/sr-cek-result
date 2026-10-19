<?php

use App\Http\Controllers\Api\ParticipantApiController;
use App\Http\Controllers\Api\SyncReceiverController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes for Cek Result VPS
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {

    // 1. Endpoint Sinkronisasi Data (Khusus push dari Edge/Laptop Event lokal)
    Route::middleware('sync.api_key')->prefix('sync')->group(function () {
        Route::post('/participants', [SyncReceiverController::class, 'sync'])->name('api.sync.participants');
        Route::get('/status', [SyncReceiverController::class, 'status'])->name('api.sync.status');
    });

    // 2. Endpoint Peserta & Cek Result (Publik / Kiosk Client)
    Route::prefix('participants')->group(function () {
        Route::get('/', [ParticipantApiController::class, 'index'])->name('api.participants.index');
        Route::get('/delta', [ParticipantApiController::class, 'delta'])->name('api.participants.delta');
        Route::get('/{identifier}', [ParticipantApiController::class, 'show'])->name('api.participants.show');
    });
});
