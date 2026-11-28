<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\CodigoVerificacionRequest;
use App\Http\Requests\LoginRequest;
use App\Mail\CodigoVerificacionMail;
use App\Models\CodigoVerificacion;
use App\Models\Usuario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class LoginController extends Controller
{
    private const SESSION_CODIGO_ID = 'login_codigo_verificacion_id';

    private const SESSION_RECORDAR = 'login_recordar';

    public function mostrarFormulario()
    {
        return view('auth.login');
    }

    /**
     * Comprueba las credenciales y envía un código sin iniciar todavía la sesión.
     */
    public function login(LoginRequest $request)
    {
        $this->limpiarVerificacionPendiente($request);

        $credenciales = [
            'correo' => $request->validated('email'),
            'password' => $request->validated('password'),
        ];

        $usuario = Usuario::where('correo', $credenciales['correo'])->first();

        if (! $usuario) {
            Log::channel('autenticacion')->warning('Usuario no encontrado', [
                'correo' => $credenciales['correo'],
                'ip' => $request->ip(),
            ]);

            return $this->respuestaCredencialesIncorrectas();
        }

        if (! Hash::check($credenciales['password'], $usuario->getAuthPassword())) {
            Log::channel('autenticacion')->warning('Contraseña incorrecta', [
                'usuario_id' => $usuario->id,
                'correo' => $usuario->correo,
                'ip' => $request->ip(),
            ]);

            return $this->respuestaCredencialesIncorrectas();
        }

        // Rota la sesión antes de guardar en ella el estado pendiente de autenticación.
        $request->session()->regenerate();
        $codigoVerificacion = DB::transaction(function () use ($usuario) {
            $usuario->codigosVerificacion()
                ->where('usado', false)
                ->update(['usado' => true]);

            return $usuario->codigosVerificacion()->create([
                'codigo' => random_int(100000, 999999),
            ]);
        });

        $request->session()->put([
            self::SESSION_CODIGO_ID => $codigoVerificacion->id,
            self::SESSION_RECORDAR => $request->boolean('recordar'),
        ]);

        Mail::to($usuario->correo)->send(new CodigoVerificacionMail(
            (string) $codigoVerificacion->codigo,
        ));

        return redirect()->route('login.codigo');
    }

    public function mostrarFormularioCodigo(Request $request)
    {
        $codigoVerificacion = $this->buscarCodigoPendiente($request);

        if (! $codigoVerificacion || $codigoVerificacion->usado) {
            $this->limpiarVerificacionPendiente($request);

            return redirect()->route('login')->withErrors([
                'email' => 'El código expiró o ya fue utilizado. Inicia sesión de nuevo.',
            ]);
        }

        if (! $codigoVerificacion->fecha_expiracion->isFuture()) {
            return $this->redirigirCodigoExpirado($request, $codigoVerificacion);
        }

        return view('auth.verificar-codigo', [
            'correo' => $codigoVerificacion->usuario->correo,
        ]);
    }

    public function verificarCodigo(CodigoVerificacionRequest $request)
    {
        $codigoVerificacion = $this->buscarCodigoPendiente($request);

        if (! $codigoVerificacion || $codigoVerificacion->usado) {
            Log::channel('autenticacion')->warning('Código de verificación inválido', [
                'usuario_id' => $codigoVerificacion?->usuario_id,
                'ip' => $request->ip(),
            ]);

            $this->limpiarVerificacionPendiente($request);

            return redirect()->route('login')->withErrors([
                'email' => 'El código expiró o ya fue utilizado. Inicia sesión de nuevo.',
            ]);
        }

        if (! $codigoVerificacion->fecha_expiracion->isFuture()) {
            return $this->redirigirCodigoExpirado($request, $codigoVerificacion);
        }

        if (! hash_equals((string) $codigoVerificacion->codigo, $request->validated('codigo'))) {
            Log::channel('autenticacion')->warning('Código de verificación inválido', [
                'usuario_id' => $codigoVerificacion->usuario_id,
                'ip' => $request->ip(),
            ]);

            return back()->withErrors([
                'codigo' => 'El código ingresado no es correcto.',
            ]);
        }

        $marcadoComoUsado = CodigoVerificacion::query()
            ->whereKey($codigoVerificacion->id)
            ->where('usado', false)
            ->where('fecha_expiracion', '>', now())
            ->update([
                'usado' => true,
                'updated_at' => now(),
            ]);

        if ($marcadoComoUsado !== 1) {
            $codigoActual = CodigoVerificacion::find($codigoVerificacion->id);

            if ($codigoActual && ! $codigoActual->fecha_expiracion->isFuture()) {
                return $this->redirigirCodigoExpirado($request, $codigoActual);
            }

            Log::channel('autenticacion')->warning('Código de verificación inválido', [
                'usuario_id' => $codigoVerificacion->usuario_id,
                'ip' => $request->ip(),
            ]);

            $this->limpiarVerificacionPendiente($request);

            return redirect()->route('login')->withErrors([
                'email' => 'El código expiró o ya fue utilizado. Inicia sesión de nuevo.',
            ]);
        }

        $usuario = $codigoVerificacion->usuario;
        $recordar = (bool) $request->session()->get(self::SESSION_RECORDAR, false);

        Auth::login($usuario, $recordar);
        $request->session()->regenerate();
        $this->limpiarVerificacionPendiente($request);

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

    private function buscarCodigoPendiente(Request $request): ?CodigoVerificacion
    {
        $id = $request->session()->get(self::SESSION_CODIGO_ID);

        if (! $id) {
            return null;
        }

        return CodigoVerificacion::with('usuario')->find($id);
    }

    private function limpiarVerificacionPendiente(Request $request): void
    {
        $request->session()->forget([
            self::SESSION_CODIGO_ID,
            self::SESSION_RECORDAR,
        ]);
    }

    private function respuestaCredencialesIncorrectas(): RedirectResponse
    {
        return back()
            ->withErrors(['email' => 'Las credenciales no coinciden con nuestros registros.'])
            ->onlyInput('email');
    }

    private function redirigirCodigoExpirado(
        Request $request,
        CodigoVerificacion $codigoVerificacion,
    ): RedirectResponse {
        Log::channel('autenticacion')->warning('Código de verificación expirado', [
            'usuario_id' => $codigoVerificacion->usuario_id,
            'codigo_verificacion_id' => $codigoVerificacion->id,
            'ip' => $request->ip(),
        ]);

        $this->limpiarVerificacionPendiente($request);

        return redirect()->route('login')->withErrors([
            'email' => 'El código expiró o ya fue utilizado. Inicia sesión de nuevo.',
        ]);
    }
}
