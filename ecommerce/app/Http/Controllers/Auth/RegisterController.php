<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegistroRequest;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function mostrarFormulario()
    {
        return view('auth.registro');
    }

    /**
     * Registra un nuevo usuario.
     * El registro público SIEMPRE crea clientes: el rol no se toma del formulario,
     * así nadie puede asignarse Empleado o Gerente por su cuenta.
     */
    public function registrar(RegistroRequest $request)
    {
        $nombreCompleto = preg_split('/\s+/', trim($request->validated('name')), 2);
        $usuario = Usuario::create([
            'nombre' => $nombreCompleto[0],
            'apellidos' => $nombreCompleto[1] ?? '',
            'correo' => $request->validated('email'),
            'clave' => $request->validated('password'),
            'rol' => Usuario::ROL_CLIENTE,
        ]);

        Auth::login($usuario);
        $request->session()->regenerate();

        return redirect()->route($usuario->rutaDashboard())
            ->with('exito', '¡Cuenta creada! Bienvenido, '.$usuario->nombre.'.');
    }
}
