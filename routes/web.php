<?php

use App\Http\Controllers\Web\CekResultWebController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CekResultWebController::class, 'index'])->name('cek-result.index');
