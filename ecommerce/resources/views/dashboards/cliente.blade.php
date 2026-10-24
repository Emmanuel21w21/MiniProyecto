@extends('layouts.app')

@section('titulo', 'Panel del cliente')

@section('contenido')
    <header class="page-heading dashboard-heading">
        <span class="eyebrow">Tu espacio personal</span>
        <h1>Hola, {{ auth()->user()->nombre }} <span aria-hidden="true">👋</span></h1>
        <p class="mb-0">Panel del <strong>Cliente</strong></p>
    </header>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card dashboard-card h-100">
                <div class="card-body">
                    <div class="feature-icon" aria-hidden="true">🛍</div>
                    <h5 class="card-title">Catálogo de productos</h5>
                    <p class="card-text">Explora y compra productos disponibles.</p>
                    <a href="{{ route('productos.index') }}" class="btn btn-primary">Ver productos</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card dashboard-card h-100">
                <div class="card-body">
                    <div class="feature-icon" aria-hidden="true">▣</div>
                    <h5 class="card-title">Mis compras</h5>
                    <p class="card-text">Consulta únicamente el historial de tus compras.</p>
                    <a href="{{ route('ventas.index') }}" class="btn btn-outline-primary">Ver mis compras</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card dashboard-card h-100">
                <div class="card-body">
                    <div class="feature-icon" aria-hidden="true">●</div>
                    <h5 class="card-title">Mi perfil</h5>
                    <p class="card-text mb-1"><strong>Nombre:</strong> {{ auth()->user()->nombre }} {{ auth()->user()->apellidos }}</p>
                    <p class="card-text"><strong>Correo:</strong> {{ auth()->user()->correo }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
