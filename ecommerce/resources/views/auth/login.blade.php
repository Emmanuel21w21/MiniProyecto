@extends('layouts.app')

@section('titulo', 'Iniciar sesión')

@section('contenido')
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card auth-card">
                <div class="card-body p-4">
                    <span class="eyebrow">Qué gusto verte de nuevo</span>
                    <h1 class="h3 mb-4">Iniciar sesión</h1>

                    <form method="POST" action="{{ route('login.enviar') }}" novalidate>
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Correo electrónico</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                   class="form-control @error('email') is-invalid @enderror" required autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" id="password" name="password"
                                   class="form-control @error('password') is-invalid @enderror" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="recordar" name="recordar" value="1">
                            <label class="form-check-label" for="recordar">Recordarme</label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Entrar</button>
                    </form>

                    <p class="text-center mt-3 mb-0">
                        ¿No tienes cuenta? <a href="{{ route('registro') }}">Regístrate</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
