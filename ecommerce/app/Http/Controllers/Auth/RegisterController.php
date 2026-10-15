<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegistroRequest;
use App\Models\User;
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
        $usuario = User::create([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'password' => $request->validated('password'), // se hashea con el cast 'hashed'
            'rol' => User::ROL_CLIENTE,
        ]);

        Auth::login($usuario);
        $request->session()->regenerate();

        return redirect()->route($usuario->rutaDashboard())
            ->with('exito', '¡Cuenta creada! Bienvenido, ' . $usuario->name . '.');
    }
}
