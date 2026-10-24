<?php

namespace App\Providers;

use App\Models\Usuario;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('administrar-tienda', fn (Usuario $usuario): bool => $usuario->esAdministrador() || $usuario->esGerente()
        );

        Gate::define('administrar-usuarios', fn (Usuario $usuario): bool => $usuario->esAdministrador()
        );
    }
}
