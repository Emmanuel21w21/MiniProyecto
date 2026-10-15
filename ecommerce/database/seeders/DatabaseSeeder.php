<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Crea un usuario de prueba por cada nivel de acceso.
     * Contraseña de todos: password
     */
    public function run(): void
    {
        $usuarios = [
            ['name' => 'Gerente Demo', 'email' => 'gerente@ecommerce.test', 'rol' => User::ROL_GERENTE],
            ['name' => 'Empleado Demo', 'email' => 'empleado@ecommerce.test', 'rol' => User::ROL_EMPLEADO],
            ['name' => 'Cliente Demo', 'email' => 'cliente@ecommerce.test', 'rol' => User::ROL_CLIENTE],
        ];

        foreach ($usuarios as $datos) {
            User::updateOrCreate(
                ['email' => $datos['email']],
                $datos + ['password' => 'password']
            );
        }
    }
}
