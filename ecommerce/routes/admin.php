<?php

use App\Http\Controllers\EstadisticasController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/estadisticas', [EstadisticasController::class, 'index'])->name('admin.estadisticas');
});