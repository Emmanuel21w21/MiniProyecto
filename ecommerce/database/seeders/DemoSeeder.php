<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Usuario;
use App\Models\Venta;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $nombres = ['Juan', 'Maria', 'Pedro', 'Ana', 'Luis', 'Sofia', 'Carlos', 'Laura', 'Jorge', 'Elena'];
        $apellidos = ['Lopez', 'Sanchez', 'Hernandez', 'Martinez', 'Garcia', 'Ramirez', 'Torres', 'Flores', 'Cruz', 'Morales'];

        Usuario::updateOrCreate(['correo' => 'gerente2@ecommerce.test'], [
            'nombre' => 'Gerente', 'apellidos' => 'Dos', 'rol' => Usuario::ROL_GERENTE, 'clave' => 'password',
        ]);

        $crear = function (string $prefijo, int $n, int $offset) use ($nombres, $apellidos) {
            $lista = [];
            for ($i = 1; $i <= $n; $i++) {
                $lista[] = Usuario::updateOrCreate(['correo' => "{$prefijo}{$i}@ecommerce.test"], [
                    'nombre' => $nombres[($i + $offset) % 10],
                    'apellidos' => $apellidos[($i * 3 + $offset) % 10],
                    'rol' => Usuario::ROL_CLIENTE,
                    'clave' => 'password',
                ]);
            }
            return collect($lista);
        };

        $vendedores = $crear('vendedor', 6, 0);
        $compradores = $crear('comprador', 10, 4);

        $categorias = Categoria::all();
        $productos = collect();
        foreach ($vendedores as $v) {
            for ($k = 1; $k <= 3; $k++) {
                $producto = Producto::updateOrCreate(['nombre' => "Producto {$v->id}-{$k}"], [
                    'descripcion' => "Producto de demostracion {$k} del vendedor {$v->nombre}.",
                    'precio' => 150 * $k + 50 * $v->id,
                    'existencia' => 10 + $k,
                    'usuario_id' => $v->id,
                ]);
                $producto->categorias()->sync($categorias->random(min(2, $categorias->count()))->pluck('id'));
                $productos->push($producto);
            }
        }

        if (Venta::count() === 0) {
            foreach ($compradores as $i => $c) {
                for ($j = 0; $j < 2; $j++) {
                    $producto = $productos[($i * 2 + $j * 5) % $productos->count()];
                    $validada = ($i + $j) % 2 === 0;
                    Venta::forceCreate([
                        'producto_id' => $producto->id,
                        'cliente_id' => $c->id,
                        'vendedor_id' => $producto->usuario_id,
                        'fecha' => now()->subDays($i + $j + 1),
                        'total' => $producto->precio,
                        'estado' => $validada ? 'validada' : 'pendiente',
                        'fecha_validacion' => $validada ? now() : null,
                    ]);
                }
            }
        }
    }
}