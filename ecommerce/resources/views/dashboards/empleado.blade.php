@extends('layouts.app')

@section('titulo', 'Panel del empleado')

@section('contenido')
    <header class="page-heading dashboard-heading">
        <span class="eyebrow">Espacio de trabajo</span>
        <h1>Hola, {{ auth()->user()->name }} <span aria-hidden="true">👋</span></h1>
        <p class="mb-0">Panel del <strong>Empleado</strong></p>
    </header>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card dashboard-card">
                <div class="card-body">
                    <div class="feature-icon" aria-hidden="true">▤</div>
                    <h5 class="card-title">Gestión de pedidos</h5>
                    <p class="card-text">Revisa y procesa los pedidos de los clientes. <span class="badge text-bg-light">Próximamente</span></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card dashboard-card">
                <div class="card-body">
                    <div class="feature-icon" aria-hidden="true">▣</div>
                    <h5 class="card-title">Inventario</h5>
                    <p class="card-text">Consulta y actualiza existencias. <span class="badge text-bg-light">Próximamente</span></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card dashboard-card">
                <div class="card-body">
                    <div class="feature-icon" aria-hidden="true">✉</div>
                    <h5 class="card-title">Atención a clientes</h5>
                    <p class="card-text">Da seguimiento a dudas y solicitudes. <span class="badge text-bg-light">Próximamente</span></p>
                </div>
            </div>
        </div>
    </div>
@endsection
