<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function mostrarFormulario()
    {
        return view('auth.login');
    }

    /**
     * Verifica las credenciales e inicia sesión.
     * Al autenticarse, redirige al dashboard que corresponde al rol del usuario.
     */
    public function login(LoginRequest $request)
    {
        $credenciales = [
            'correo' => $request->validated('email'),
            'password' => $request->validated('password'),
        ];

        if (! Auth::attempt($credenciales, $request->boolean('recordar'))) {
            Log::channel('autenticacion')->warning('Login incorrecto', [
                'correo' => $credenciales['correo'],
                'ip' => $request->ip(),
            ]);

            return back()
                ->withErrors(['email' => 'Las credenciales no coinciden con nuestros registros.'])
                ->onlyInput('email');
        }

        // Evita la fijación de sesión.
        $request->session()->regenerate();
        $usuario = $request->user();

        Log::channel('autenticacion')->info('Login exitoso', [
            'usuario_id' => $usuario->id,
            'correo' => $usuario->correo,
            'ip' => $request->ip(),
        ]);

        return redirect()->route($usuario->rutaDashboard());
    }

    public function logout(Request $request)
    {
        $usuario = $request->user();
        Log::channel('autenticacion')->info('Logout', [
            'usuario_id' => $usuario->id,
            'correo' => $usuario->correo,
            'ip' => $request->ip(),
        ]);

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('inicio')->with('exito', 'Sesión cerrada correctamente.');
    }
}
