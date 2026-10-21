<?php

use App\Http\Controllers\CategoryTimeController;
use App\Http\Controllers\Web\CekResultWebController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CekResultWebController::class, 'index'])->name('cek-result.index');
Route::get('/system-time', [CekResultWebController::class, 'systemTime'])->name('system.time');
Route::put('/participants/{id}', [CekResultWebController::class, 'updateParticipant'])->name('participants.update');

// Category Times (Time Settings) CRUD
Route::get('/category-times', [CategoryTimeController::class, 'index'])->name('category-times.index');
Route::post('/category-times', [CategoryTimeController::class, 'store'])->name('category-times.store');
Route::put('/category-times/{id}', [CategoryTimeController::class, 'update'])->name('category-times.update');
Route::delete('/category-times/{id}', [CategoryTimeController::class, 'destroy'])->name('category-times.destroy');
