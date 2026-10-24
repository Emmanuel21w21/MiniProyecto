@extends('layouts.app')
@section('titulo', 'Ventas')
@section('contenido')
    <div class="d-flex justify-content-between align-items-end mb-4">
        <header class="page-heading mb-0"><span class="eyebrow">Operaciones</span><h1 class="mb-0">Ventas</h1></header>
        @can('create', \App\Models\Venta::class)
            <a href="{{ route('ventas.create') }}" class="btn btn-primary">Comprar producto</a>
        @endcan
    </div>
    <div class="card"><div class="table-responsive"><table class="table table-hover align-middle mb-0">
        <thead><tr><th>#</th><th>Producto</th><th>Cliente</th><th>Vendedor</th><th>Fecha</th><th>Total</th><th>Acciones</th></tr></thead><tbody>
        @forelse($ventas as $venta)
            <tr>
                <td>{{ $venta->id }}</td><td>{{ $venta->producto->nombre }}</td>
                <td>{{ $venta->cliente->nombre }} {{ $venta->cliente->apellidos }}</td>
                <td>{{ $venta->vendedor->nombre }} {{ $venta->vendedor->apellidos }}</td>
                <td>{{ $venta->fecha->format('d/m/Y') }}</td><td>${{ number_format((float) $venta->total, 2) }}</td>
                <td>@can('update', $venta)
                    <a href="{{ route('ventas.edit', $venta) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                    <form method="POST" action="{{ route('ventas.destroy', $venta) }}" class="d-inline" onsubmit="return confirm('¿Eliminar la venta?')">
                        @csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Eliminar</button>
                    </form>
                @endcan</td>
            </tr>
        @empty <tr><td colspan="7" class="text-center">No hay ventas registradas.</td></tr>
        @endforelse
        </tbody></table></div></div>
    <div class="mt-3">{{ $ventas->links('pagination::bootstrap-5') }}</div>
@endsection
