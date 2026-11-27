@extends('layouts.app')
@section('titulo', 'Ventas')
@section('contenido')
    <div class="d-flex justify-content-between align-items-end mb-4">
        <header class="page-heading mb-0">
            @can('viewAll', \App\Models\Venta::class)
                <span class="eyebrow">Operaciones</span>
                <h1 class="mb-0">Ventas</h1>
            @else
                <span class="eyebrow">Tu historial</span>
                <h1 class="mb-0">Mis compras</h1>
            @endcan
        </header>
        @can('create', \App\Models\Venta::class)
            <a href="{{ route('ventas.create') }}" class="btn btn-primary">Comprar producto</a>
        @endcan
    </div>
    <div class="card"><div class="table-responsive"><table class="table table-hover align-middle mb-0">
        <thead>
            <tr>
                <th>#</th>
                <th>Producto</th>
                @can('viewAll', \App\Models\Venta::class)<th>Cliente</th>@endcan
                <th>Vendedor</th>
                <th>Fecha</th>
                <th>Total</th>
                @can('viewAll', \App\Models\Venta::class)<th>Acciones</th>@endcan
            </tr>
        </thead>
        <tbody>
        @forelse($ventas as $venta)
            <tr>
                <td>{{ $venta->id }}</td><td>{{ $venta->producto->nombre }}</td>
                @can('viewAll', \App\Models\Venta::class)<td>{{ $venta->cliente->nombre }} {{ $venta->cliente->apellidos }}</td>@endcan
                <td>{{ $venta->vendedor->nombre }} {{ $venta->vendedor->apellidos }}</td>
                <td>{{ $venta->fecha->format('d/m/Y') }}</td><td>${{ number_format((float) $venta->total, 2) }}</td>
                @can('viewAll', \App\Models\Venta::class)
                    <td>
                        @can('update', $venta)
                            <a href="{{ route('ventas.edit', $venta) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                            <form method="POST" action="{{ route('ventas.destroy', $venta) }}" class="d-inline" onsubmit="return confirm('¿Eliminar la venta?')">
                                @csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Eliminar</button>
                            </form>
                        @endcan
                    </td>
                @endcan
            </tr>
        @empty
            @can('viewAll', \App\Models\Venta::class)
                <tr>
                    <td colspan="7" class="text-center">
                        No hay ventas registradas.
                    </td>
                </tr>
            @else
                <tr>
                    <td colspan="5" class="text-center">
                        Aún no has realizado compras.
                    </td>
                </tr>
            @endcan
        @endforelse
        </tbody></table></div></div>
    <div class="mt-3">{{ $ventas->links('pagination::bootstrap-5') }}</div>
@endsection
