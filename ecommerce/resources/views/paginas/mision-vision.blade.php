@extends('layouts.app')

@section('titulo', 'Misión y visión')

@section('contenido')
    <h1 class="mb-3">Misión y visión</h1>
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h4 class="card-title">Misión</h4>
                    <p class="card-text">
                        Facilitar el acceso a productos de calidad mediante una plataforma de comercio electrónico
                        confiable, con un servicio al cliente cercano y procesos claros para todo el equipo.
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h4 class="card-title">Visión</h4>
                    <p class="card-text">
                        Ser una tienda en línea de referencia por su confianza, innovación y compromiso con la
                        satisfacción de nuestros clientes.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
