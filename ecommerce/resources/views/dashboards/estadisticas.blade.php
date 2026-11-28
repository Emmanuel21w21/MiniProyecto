@extends('layouts.app')

@section('titulo', 'Estadísticas')

@section('contenido')
    <header class="page-heading dashboard-heading">
        <span class="eyebrow">Administración general</span>
        <h1>Estadísticas</h1>
        <p class="mb-0">Resumen de usuarios, productos y ventas.</p>
    </header>
    <div class="row g-3 mb-4">
        @foreach (['Usuarios' => $totalUsuarios, 'Vendedores' => $totalVendedores, 'Compradores' => $totalCompradores] as $etiqueta => $total)
            <div class="col-md-4"><div class="card stat-card"><div class="card-body">
                <div class="stat-value">{{ $total }}</div><div class="stat-label">{{ $etiqueta }}</div>
            </div></div></div>
        @endforeach
    </div>
    <div class="card mb-4"><div class="card-body">
        <h2 class="h5">Producto más vendido</h2>
        @if ($productoMasVendido && $productoMasVendido->ventas_count > 0)
            <p class="mb-0">{{ $productoMasVendido->nombre }} ({{ $productoMasVendido->ventas_count }} ventas)</p>
        @else
            <p class="mb-0">Aún no hay ventas.</p>
        @endif
    </div></div>
    <div class="card"><div class="table-responsive"><table class="table align-middle mb-0">
        <thead><tr><th>Categoría</th><th>Productos</th><th>Comprador más frecuente</th><th>Compras</th></tr></thead>
        <tbody>
        @forelse ($porCategoria as $fila)
            <tr><td>{{ $fila['categoria'] }}</td><td>{{ $fila['productos'] }}</td><td>{{ $fila['comprador'] ? $fila['comprador']->nombre . ' ' . $fila['comprador']->apellidos : 'Sin compras' }}</td><td>{{ $fila['compras'] }}</td></tr>
        @empty
            <tr><td colspan="4" class="text-center">No hay categorías.</td></tr>
        @endforelse
        </tbody></table></div></div>
@endsection