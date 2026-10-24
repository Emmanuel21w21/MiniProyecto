@extends('layouts.app')

@section('titulo', 'Panel del administrador')

@section('contenido')
    <header class="page-heading dashboard-heading">
        <span class="eyebrow">Administración general</span>
        <h1>Hola, {{ auth()->user()->nombre }}</h1>
        <p class="mb-0">Control de usuarios, catálogo y ventas.</p>
    </header>
    <div class="row g-3 mb-4">
        @foreach(['Usuarios' => $totalUsuarios, 'Productos' => $totalProductos, 'Ventas' => $totalVentas] as $etiqueta => $total)
            <div class="col-md-4"><div class="card stat-card"><div class="card-body">
                <div class="stat-value">{{ $total }}</div><div class="stat-label">{{ $etiqueta }}</div>
            </div></div></div>
        @endforeach
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a class="btn btn-primary" href="{{ route('usuarios.index') }}">Administrar usuarios</a>
        <a class="btn btn-outline-primary" href="{{ route('productos.index') }}">Administrar productos</a>
        <a class="btn btn-outline-primary" href="{{ route('categorias.index') }}">Administrar categorías</a>
        <a class="btn btn-outline-primary" href="{{ route('ventas.index') }}">Consultar ventas</a>
    </div>
@endsection
