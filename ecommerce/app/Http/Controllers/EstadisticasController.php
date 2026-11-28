<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Usuario;
use Illuminate\Http\Request;

class EstadisticasController extends Controller
{
    public function index(Request $request)
    {
        abort_unless($request->user()->esAdministrador(), 403);

        $categorias = Categoria::withCount('productos')
            ->with('productos.ventas.cliente')
            ->orderBy('nombre')
            ->get();

        $porCategoria = $categorias->map(function ($categoria) {
            $compras = $categoria->productos->flatMap->ventas;
            $top = $compras->groupBy('cliente_id')->sortByDesc(fn ($grupo) => $grupo->count())->first();

            return [
                'categoria' => $categoria->nombre,
                'productos' => $categoria->productos_count,
                'comprador' => $top?->first()->cliente,
                'compras' => $top?->count() ?? 0,
            ];
        });

        return view('dashboards.estadisticas', [
            'totalUsuarios' => Usuario::count(),
            'totalVendedores' => Usuario::has('productos')->count(),
            'totalCompradores' => Usuario::has('ventasComoCliente')->count(),
            'productoMasVendido' => Producto::withCount('ventas')->orderByDesc('ventas_count')->first(),
            'porCategoria' => $porCategoria,
        ]);
    }
}