@extends('layouts.app')

@section('titulo', 'Panel del gerente')

@section('contenido')
    <h1 class="mb-1">Hola, {{ auth()->user()->name }} 👋</h1>
    <p class="text-muted mb-4">Panel del <strong>Gerente</strong> · herramientas de administración</p>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card text-center shadow-sm"><div class="card-body">
                <div class="fs-2 fw-bold">{{ $totalUsuarios }}</div>
                <div class="text-muted">Usuarios</div>
            </div></div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card text-center shadow-sm"><div class="card-body">
                <div class="fs-2 fw-bold">{{ $totalClientes }}</div>
                <div class="text-muted">Clientes</div>
            </div></div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card text-center shadow-sm"><div class="card-body">
                <div class="fs-2 fw-bold">{{ $totalEmpleados }}</div>
                <div class="text-muted">Empleados</div>
            </div></div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card text-center shadow-sm"><div class="card-body">
                <div class="fs-2 fw-bold">{{ $totalGerentes }}</div>
                <div class="text-muted">Gerentes</div>
            </div></div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">👥 Administrar usuarios</h5>
                    <p class="card-text">Consulta, crea, edita y elimina usuarios y sus roles.</p>
                    <a href="{{ route('usuarios.index') }}" class="btn btn-primary">Ir a usuarios</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">📈 Reportes de ventas</h5>
                    <p class="card-text">Indicadores y ventas del negocio. <span class="badge text-bg-light">Próximamente</span></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">⚙️ Configuración de la tienda</h5>
                    <p class="card-text">Parámetros generales del e-commerce. <span class="badge text-bg-light">Próximamente</span></p>
                </div>
            </div>
        </div>
    </div>
@endsection
