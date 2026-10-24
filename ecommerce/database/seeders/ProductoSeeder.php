<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Usuario;
use Illuminate\Database\Seeder;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        $vendedor = Usuario::where('correo', 'gerente@ecommerce.test')->firstOrFail();
        $productos = [
            ['nombre' => 'Consola de videojuegos', 'descripcion' => 'Consola de última generación.', 'precio' => 8999.00, 'existencia' => 8, 'categoria' => 'Consolas'],
            ['nombre' => 'Control inalámbrico', 'descripcion' => 'Control inalámbrico para videojuegos.', 'precio' => 1299.00, 'existencia' => 20, 'categoria' => 'Accesorios'],
        ];

        foreach ($productos as $datos) {
            $categoria = $datos['categoria'];
            unset($datos['categoria']);
            $producto = Producto::updateOrCreate(
                ['nombre' => $datos['nombre']],
                $datos + ['usuario_id' => $vendedor->id]
            );
            $producto->categorias()->sync(Categoria::where('nombre', $categoria)->pluck('id'));
        }
    }
}
