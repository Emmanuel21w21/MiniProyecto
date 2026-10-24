@extends('layouts.app')

@section('titulo', 'Panel del gerente')

@section('contenido')
    <header class="page-heading dashboard-heading">
        <span class="eyebrow">Resumen de la tienda</span>
        <h1>Hola, {{ auth()->user()->nombre }} <span aria-hidden="true">👋</span></h1>
        <p class="mb-0">Panel del <strong>Gerente</strong> · herramientas de administración</p>
    </header>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card stat-card"><div class="card-body">
                <div class="stat-value">{{ $totalUsuarios }}</div>
                <div class="stat-label">Usuarios</div>
            </div></div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card"><div class="card-body">
                <div class="stat-value">{{ $totalClientes }}</div>
                <div class="stat-label">Clientes</div>
            </div></div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card"><div class="card-body">
                <div class="stat-value">{{ $totalAdministradores }}</div>
                <div class="stat-label">Administradores</div>
            </div></div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card"><div class="card-body">
                <div class="stat-value">{{ $totalGerentes }}</div>
                <div class="stat-label">Gerentes</div>
            </div></div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card dashboard-card">
                <div class="card-body">
                    <div class="feature-icon" aria-hidden="true">♙</div>
                    <h5 class="card-title">Administrar usuarios</h5>
                    <p class="card-text">Consulta usuarios y edita los perfiles de clientes.</p>
                    <a href="{{ route('usuarios.index') }}" class="btn btn-primary">Ir a usuarios</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card dashboard-card">
                <div class="card-body">
                    <div class="feature-icon" aria-hidden="true">↗</div>
                    <h5 class="card-title">Reportes de ventas</h5>
                    <p class="card-text">Consulta las operaciones de la tienda.</p>
                    <a href="{{ route('ventas.index') }}" class="btn btn-outline-primary">Ver ventas</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card dashboard-card">
                <div class="card-body">
                    <div class="feature-icon" aria-hidden="true">⚙</div>
                    <h5 class="card-title">Configuración de la tienda</h5>
                    <p class="card-text">Administra el catálogo de productos y categorías.</p>
                    <a href="{{ route('productos.index') }}" class="btn btn-outline-primary">Ver catálogo</a>
                </div>
            </div>
        </div>
    </div>
@endsection
