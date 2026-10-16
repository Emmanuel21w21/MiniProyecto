@extends('layouts.app')

@section('titulo', 'Panel del cliente')

@section('contenido')
    <header class="page-heading dashboard-heading">
        <span class="eyebrow">Tu espacio personal</span>
        <h1>Hola, {{ auth()->user()->name }} <span aria-hidden="true">👋</span></h1>
        <p class="mb-0">Panel del <strong>Cliente</strong></p>
    </header>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card dashboard-card">
                <div class="card-body">
                    <div class="feature-icon" aria-hidden="true">🛍</div>
                    <h5 class="card-title">Catálogo de productos</h5>
                    <p class="card-text">Explora los productos disponibles. <span class="badge text-bg-light">Próximamente</span></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card dashboard-card">
                <div class="card-body">
                    <div class="feature-icon" aria-hidden="true">▣</div>
                    <h5 class="card-title">Mis pedidos</h5>
                    <p class="card-text">Consulta el estado de tus compras. <span class="badge text-bg-light">Próximamente</span></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card dashboard-card">
                <div class="card-body">
                    <div class="feature-icon" aria-hidden="true">●</div>
                    <h5 class="card-title">Mi perfil</h5>
                    <p class="card-text mb-1"><strong>Nombre:</strong> {{ auth()->user()->name }}</p>
                    <p class="card-text"><strong>Correo:</strong> {{ auth()->user()->email }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
