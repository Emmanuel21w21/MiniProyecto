@extends('layouts.app')
@section('titulo', 'Ventas')
@section('contenido')
    <div class="d-flex justify-content-between align-items-end mb-4">
        <header class="page-heading mb-0">
            <span class="eyebrow">{{ auth()->user()->esCliente() ? 'Tu historial' : 'Operaciones' }}</span>
            <h1 class="mb-0">{{ auth()->user()->esCliente() ? 'Mis compras' : 'Ventas' }}</h1>
        </header>
        @can('create', \App\Models\Venta::class)
            <a href="{{ route('ventas.create') }}" class="btn btn-primary">Comprar producto</a>
        @endcan
    </div>
    <div class="card"><div class="table-responsive"><table class="table table-hover align-middle mb-0">
        <thead><tr><th>#</th><th>Producto</th>@unless(auth()->user()->esCliente())<th>Cliente</th>@endunless<th>Vendedor</th><th>Fecha</th><th>Total</th>@unless(auth()->user()->esCliente())<th>Acciones</th>@endunless</tr></thead><tbody>
        @forelse($ventas as $venta)
            <tr>
                <td>{{ $venta->id }}</td><td>{{ $venta->producto->nombre }}</td>
                @unless(auth()->user()->esCliente())<td>{{ $venta->cliente->nombre }} {{ $venta->cliente->apellidos }}</td>@endunless
                <td>{{ $venta->vendedor->nombre }} {{ $venta->vendedor->apellidos }}</td>
                <td>{{ $venta->fecha->format('d/m/Y') }}</td><td>${{ number_format((float) $venta->total, 2) }}</td>
                @unless(auth()->user()->esCliente())<td>@can('update', $venta)
                    <a href="{{ route('ventas.edit', $venta) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                    <form method="POST" action="{{ route('ventas.destroy', $venta) }}" class="d-inline" onsubmit="return confirm('¿Eliminar la venta?')">
                        @csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Eliminar</button>
                    </form>
                @endcan</td>@endunless
            </tr>
        @empty <tr><td colspan="{{ auth()->user()->esCliente() ? 5 : 7 }}" class="text-center">{{ auth()->user()->esCliente() ? 'Aún no has realizado compras.' : 'No hay ventas registradas.' }}</td></tr>
        @endforelse
        </tbody></table></div></div>
    <div class="mt-3">{{ $ventas->links('pagination::bootstrap-5') }}</div>
@endsection
