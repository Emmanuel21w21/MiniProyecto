<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaginaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Middleware\RoleMiddleware;
use App\Models\User;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rutas públicas (cualquier visitante, incluso anónimo)
|--------------------------------------------------------------------------
*/
Route::get('/', [PaginaController::class, 'inicio'])->name('inicio');
Route::get('/quienes-somos', [PaginaController::class, 'quienesSomos'])->name('quienes-somos');
Route::get('/mision-vision', [PaginaController::class, 'misionVision'])->name('mision-vision');
Route::get('/ubicacion', [PaginaController::class, 'ubicacion'])->name('ubicacion');
Route::get('/contacto', [PaginaController::class, 'contacto'])->name('contacto');

/*
|--------------------------------------------------------------------------
| Solo invitados (usuarios sin sesión iniciada)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'mostrarFormulario'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])
        ->middleware('throttle:5,1') // máx. 5 intentos por minuto
        ->name('login.enviar');

    Route::get('/registro', [RegisterController::class, 'mostrarFormulario'])->name('registro');
    Route::post('/registro', [RegisterController::class, 'registrar'])->name('registro.enviar');
});

/*
|--------------------------------------------------------------------------
| Usuarios autenticados
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // /dashboard envía a cada usuario a su panel según el rol.
    Route::get('/dashboard', [DashboardController::class, 'redirigir'])->name('dashboard');

    // Dashboard del Cliente
    Route::get('/dashboard/cliente', [DashboardController::class, 'cliente'])
        ->middleware(RoleMiddleware::class . ':' . User::ROL_CLIENTE)
        ->name('dashboard.cliente');

    // Dashboard del Empleado
    Route::get('/dashboard/empleado', [DashboardController::class, 'empleado'])
        ->middleware(RoleMiddleware::class . ':' . User::ROL_EMPLEADO)
        ->name('dashboard.empleado');

    // Dashboard y herramientas de administración del Gerente
    Route::middleware(RoleMiddleware::class . ':' . User::ROL_GERENTE)->group(function () {
        Route::get('/dashboard/gerente', [DashboardController::class, 'gerente'])
            ->name('dashboard.gerente');

        // CRUD de usuarios (index, create, store, edit, update, destroy)
        Route::resource('usuarios', UsuarioController::class)
            ->except('show')
            ->parameters(['usuarios' => 'usuario']);
    });
});
