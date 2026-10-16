@extends('layouts.app')

@section('titulo', 'Registrarse')

@section('contenido')
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="card auth-card">
                <div class="card-body p-4">
                    <span class="eyebrow">Empieza tu experiencia</span>
                    <h1 class="h3 mb-4">Crear cuenta</h1>

                    <form method="POST" action="{{ route('registro.enviar') }}" novalidate>
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre completo</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}"
                                   class="form-control @error('name') is-invalid @enderror" required autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Correo electrónico</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                   class="form-control @error('email') is-invalid @enderror" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" id="password" name="password"
                                   class="form-control @error('password') is-invalid @enderror" required>
                            <div class="form-text">Mínimo 8 caracteres, con al menos una letra y un número.</div>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirmar contraseña</label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                   class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Registrarme</button>
                    </form>

                    <p class="text-center mt-3 mb-0">
                        ¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
