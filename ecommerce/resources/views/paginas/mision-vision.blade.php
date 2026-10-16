@extends('layouts.app')

@section('titulo', 'Misión y visión')

@section('contenido')
    <header class="page-heading">
        <span class="eyebrow">Lo que nos mueve</span>
        <h1>Misión y visión</h1>
        <p class="mb-0">El propósito que guía nuestro trabajo y el futuro que estamos construyendo.</p>
    </header>
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card feature-card h-100">
                <div class="card-body">
                    <div class="feature-icon" aria-hidden="true">✦</div>
                    <h2 class="h4 card-title">Misión</h2>
                    <p class="card-text">
                        Facilitar el acceso a productos de calidad mediante una plataforma de comercio electrónico
                        confiable, con un servicio al cliente cercano y procesos claros para todo el equipo.
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card feature-card h-100">
                <div class="card-body">
                    <div class="feature-icon" aria-hidden="true">◎</div>
                    <h2 class="h4 card-title">Visión</h2>
                    <p class="card-text">
                        Ser una tienda en línea de referencia por su confianza, innovación y compromiso con la
                        satisfacción de nuestros clientes.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
