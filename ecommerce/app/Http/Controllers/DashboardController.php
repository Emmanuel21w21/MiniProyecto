<?php

namespace App\Http\Controllers;

use App\Models\User;
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

    public function empleado()
    {
        return view('dashboards.empleado');
    }

    public function gerente()
    {
        return view('dashboards.gerente', [
            'totalUsuarios' => User::count(),
            'totalClientes' => User::where('rol', User::ROL_CLIENTE)->count(),
            'totalEmpleados' => User::where('rol', User::ROL_EMPLEADO)->count(),
            'totalGerentes' => User::where('rol', User::ROL_GERENTE)->count(),
        ]);
    }
}
