@extends('layouts.app')

@section('titulo', 'Inicio')

@section('contenido')
    <section class="home-hero">
        <div class="home-hero-content">
            <span class="eyebrow">Tu tienda gamer · GG, buena elección</span>
            <h1>El siguiente nivel empieza aquí.</h1>
            <p>Videojuegos, consolas y cultura gamer se encuentran en un solo lugar. Encuentra tu próxima aventura.</p>

            @guest
                <a href="{{ route('login') }}" class="btn btn-light btn-lg me-2">Iniciar sesión</a>
                <a href="{{ route('registro') }}" class="btn btn-outline-light btn-lg">Crear cuenta</a>
            @endguest
            @auth
                <a href="{{ route('dashboard') }}" class="btn btn-light btn-lg">Ir a mi panel <span aria-hidden="true">→</span></a>
            @endauth
        </div>
        <div class="home-hero-art" aria-hidden="true">
            <div class="hero-orbit">🎮</div>
        </div>
    </section>

    <section aria-labelledby="explora-heading">
        <div class="section-heading">
            <div>
                <span class="eyebrow">Más que una tienda</span>
                <h2 id="explora-heading">Tu comunidad gamer empieza aquí</h2>
                <p class="text-muted mb-0">Conoce al equipo, nuestra misión y cómo ponerte en contacto.</p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <article class="card feature-card">
                    <div class="card-body">
                        <div class="feature-icon" aria-hidden="true">⌘</div>
                        <h3 class="h5 card-title">Quiénes somos</h3>
                        <p class="card-text">Conoce nuestra historia y al equipo detrás de la tienda.</p>
                        <a href="{{ route('quienes-somos') }}" class="stretched-link">Conoce nuestra historia <span aria-hidden="true">→</span></a>
                    </div>
                </article>
            </div>
            <div class="col-md-4">
                <article class="card feature-card">
                    <div class="card-body">
                        <div class="feature-icon" aria-hidden="true">◎</div>
                        <h3 class="h5 card-title">Misión y visión</h3>
                        <p class="card-text">Los valores que nos guían y hacia dónde queremos llegar.</p>
                        <a href="{{ route('mision-vision') }}" class="stretched-link">Lo que nos inspira <span aria-hidden="true">→</span></a>
                    </div>
                </article>
            </div>
            <div class="col-md-4">
                <article class="card feature-card">
                    <div class="card-body">
                        <div class="feature-icon" aria-hidden="true">↗</div>
                        <h3 class="h5 card-title">Contáctanos</h3>
                        <p class="card-text">¿Tienes dudas o comentarios? Estamos para ayudarte.</p>
                        <a href="{{ route('contacto') }}" class="stretched-link">Hablemos <span aria-hidden="true">→</span></a>
                    </div>
                </article>
            </div>
        </div>
    </section>
@endsection
