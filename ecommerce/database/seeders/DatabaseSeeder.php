<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $usuarios = [
            ['nombre' => 'Administrador', 'apellidos' => 'Demo', 'correo' => 'admin@ecommerce.test', 'rol' => Usuario::ROL_ADMINISTRADOR],
            ['nombre' => 'Gerente', 'apellidos' => 'Demo', 'correo' => 'gerente@ecommerce.test', 'rol' => Usuario::ROL_GERENTE],
            ['nombre' => 'Cliente', 'apellidos' => 'Demo', 'correo' => 'cliente@ecommerce.test', 'rol' => Usuario::ROL_CLIENTE],
        ];

        foreach ($usuarios as $datos) {
            Usuario::updateOrCreate(
                ['correo' => $datos['correo']],
                $datos + ['clave' => 'password']
            );
        }

        $this->call([
            CategoriaSeeder::class,
            ProductoSeeder::class,
        ]);
    }
}
