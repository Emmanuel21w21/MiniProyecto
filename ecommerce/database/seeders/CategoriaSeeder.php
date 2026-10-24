<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['nombre' => 'Consolas', 'descripcion' => 'Consolas de videojuegos.'],
            ['nombre' => 'Videojuegos', 'descripcion' => 'Juegos para distintas plataformas.'],
            ['nombre' => 'Accesorios', 'descripcion' => 'Controles y accesorios gamer.'],
        ] as $categoria) {
            Categoria::updateOrCreate(['nombre' => $categoria['nombre']], $categoria);
        }
    }
}
