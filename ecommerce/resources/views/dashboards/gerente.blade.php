@extends('layouts.app')

@section('titulo', 'Panel del gerente')

@section('contenido')
    <header class="page-heading dashboard-heading">
        <span class="eyebrow">Resumen de la tienda</span>
        <h1>Hola, {{ auth()->user()->name }} <span aria-hidden="true">👋</span></h1>
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
                <div class="stat-value">{{ $totalEmpleados }}</div>
                <div class="stat-label">Empleados</div>
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
                    <p class="card-text">Consulta, crea, edita y elimina usuarios y sus roles.</p>
                    <a href="{{ route('usuarios.index') }}" class="btn btn-primary">Ir a usuarios</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card dashboard-card">
                <div class="card-body">
                    <div class="feature-icon" aria-hidden="true">↗</div>
                    <h5 class="card-title">Reportes de ventas</h5>
                    <p class="card-text">Indicadores y ventas del negocio. <span class="badge text-bg-light">Próximamente</span></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card dashboard-card">
                <div class="card-body">
                    <div class="feature-icon" aria-hidden="true">⚙</div>
                    <h5 class="card-title">Configuración de la tienda</h5>
                    <p class="card-text">Parámetros generales del e-commerce. <span class="badge text-bg-light">Próximamente</span></p>
                </div>
            </div>
        </div>
    </div>
@endsection
