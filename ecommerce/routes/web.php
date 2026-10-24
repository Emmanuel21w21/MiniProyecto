<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaginaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\VentaController;
use App\Http\Middleware\RoleMiddleware;
use App\Models\Usuario;
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
        ->middleware(RoleMiddleware::class.':'.Usuario::ROL_CLIENTE)
        ->name('dashboard.cliente');

    Route::get('/dashboard/administrador', [DashboardController::class, 'administrador'])
        ->middleware(RoleMiddleware::class.':'.Usuario::ROL_ADMINISTRADOR)
        ->name('dashboard.administrador');
    Route::get('/dashboard/gerente', [DashboardController::class, 'gerente'])
        ->middleware(RoleMiddleware::class.':'.Usuario::ROL_GERENTE)
        ->name('dashboard.gerente');

    Route::get('/productos', [ProductoController::class, 'index'])->name('productos.index');
    Route::get('/categorias', [CategoriaController::class, 'index'])->name('categorias.index');
    Route::resource('ventas', VentaController::class)->except('show');

    Route::resource('usuarios', UsuarioController::class)
        ->except('show')
        ->parameters(['usuarios' => 'usuario']);

    Route::middleware('can:administrar-tienda')->group(function () {
        Route::get('/productos/create', [ProductoController::class, 'create'])->name('productos.create');
        Route::post('/productos', [ProductoController::class, 'store'])->name('productos.store');
        Route::get('/productos/{producto}/edit', [ProductoController::class, 'edit'])->name('productos.edit');
        Route::put('/productos/{producto}', [ProductoController::class, 'update'])->name('productos.update');
        Route::delete('/productos/{producto}', [ProductoController::class, 'destroy'])->name('productos.destroy');

        Route::get('/categorias/create', [CategoriaController::class, 'create'])->name('categorias.create');
        Route::post('/categorias', [CategoriaController::class, 'store'])->name('categorias.store');
        Route::get('/categorias/{categoria}/edit', [CategoriaController::class, 'edit'])->name('categorias.edit');
        Route::put('/categorias/{categoria}', [CategoriaController::class, 'update'])->name('categorias.update');
        Route::delete('/categorias/{categoria}', [CategoriaController::class, 'destroy'])->name('categorias.destroy');
    });

});
