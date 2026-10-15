@extends('layouts.app')

@section('titulo', 'Inicio')

@section('contenido')
    <div class="hero rounded-3 p-5 mb-4 text-center">
        <h1 class="display-5 fw-bold">Bienvenido a Mi E-commerce</h1>
        <p class="lead mb-4">Todo lo que necesitas, en un solo lugar y con atención cercana.</p>

        @guest
            <a href="{{ route('login') }}" class="btn btn-light btn-lg me-2">Iniciar sesión</a>
            <a href="{{ route('registro') }}" class="btn btn-outline-light btn-lg">Crear cuenta</a>
        @endguest
        @auth
            <a href="{{ route('dashboard') }}" class="btn btn-light btn-lg">Ir a mi panel</a>
        @endauth
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Quiénes somos</h5>
                    <p class="card-text">Conoce nuestra historia y al equipo detrás de la tienda.</p>
                    <a href="{{ route('quienes-somos') }}" class="stretched-link">Leer más</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Misión y visión</h5>
                    <p class="card-text">Hacia dónde vamos y los valores que nos guían.</p>
                    <a href="{{ route('mision-vision') }}" class="stretched-link">Leer más</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Contáctanos</h5>
                    <p class="card-text">¿Dudas o comentarios? Estamos para ayudarte.</p>
                    <a href="{{ route('contacto') }}" class="stretched-link">Ver datos de contacto</a>
                </div>
            </div>
        </div>
    </div>
@endsection
