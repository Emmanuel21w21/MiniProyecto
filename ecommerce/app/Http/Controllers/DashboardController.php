<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Usuario;
use App\Models\Venta;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Redirige a cada usuario a su dashboard según el rol.
     */
    public function redirigir(Request $request)
    {
        return redirect()->route($request->user()->rutaDashboard());
    }

    public function cliente()
    {
        return view('dashboards.cliente');
    }

    public function administrador()
    {
        return view('dashboards.administrador', [
            'totalUsuarios' => Usuario::count(),
            'totalProductos' => Producto::count(),
            'totalVentas' => Venta::count(),
        ]);
    }

    public function gerente()
    {
        return view('dashboards.gerente', [
            'totalUsuarios' => Usuario::count(),
            'totalClientes' => Usuario::where('rol', Usuario::ROL_CLIENTE)->count(),
            'totalAdministradores' => Usuario::where('rol', Usuario::ROL_ADMINISTRADOR)->count(),
            'totalGerentes' => Usuario::where('rol', Usuario::ROL_GERENTE)->count(),
        ]);
    }
}
