@extends('layouts.app')

@section('titulo', 'Verificar código')

@section('contenido')
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card auth-card">
                <div class="card-body p-4">
                    <span class="eyebrow">Verificación de seguridad</span>
                    <h1 class="h3 mb-3">Ingresa tu código</h1>
                    <p class="text-muted">
                        Enviamos un código de seis dígitos a <strong>{{ $correo }}</strong>.
                        El código vence en 5 minutos.
                    </p>

                    <form method="POST" action="{{ route('login.codigo.verificar') }}" novalidate>
                        @csrf

                        <div class="mb-3">
                            <label for="codigo" class="form-label">Código de verificación</label>
                            <input type="text" id="codigo" name="codigo" inputmode="numeric"
                                   pattern="[0-9]{6}" maxlength="6" autocomplete="one-time-code"
                                   class="form-control @error('codigo') is-invalid @enderror"
                                   required autofocus>
                            @error('codigo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Verificar e iniciar sesión</button>
                    </form>

                    <p class="text-center mt-3 mb-0">
                        <a href="{{ route('login') }}">Volver al inicio de sesión</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
