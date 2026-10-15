@extends('layouts.app')

@section('titulo', 'Panel del empleado')

@section('contenido')
    <h1 class="mb-1">Hola, {{ auth()->user()->name }} 👋</h1>
    <p class="text-muted mb-4">Panel del <strong>Empleado</strong></p>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">📋 Gestión de pedidos</h5>
                    <p class="card-text">Revisa y procesa los pedidos de los clientes. <span class="badge text-bg-light">Próximamente</span></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">📦 Inventario</h5>
                    <p class="card-text">Consulta y actualiza existencias. <span class="badge text-bg-light">Próximamente</span></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">💬 Atención a clientes</h5>
                    <p class="card-text">Da seguimiento a dudas y solicitudes. <span class="badge text-bg-light">Próximamente</span></p>
                </div>
            </div>
        </div>
    </div>
@endsection
