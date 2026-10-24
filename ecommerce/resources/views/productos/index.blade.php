@extends('layouts.app')

@section('titulo', 'Productos')

@section('contenido')
    <div class="d-flex justify-content-between align-items-end mb-4">
        <header class="page-heading mb-0"><span class="eyebrow">Catálogo</span><h1 class="mb-0">Productos</h1></header>
        @can('create', \App\Models\Producto::class)
            <a href="{{ route('productos.create') }}" class="btn btn-primary">+ Nuevo producto</a>
        @endcan
    </div>
    <div class="row g-3">
        @forelse($productos as $producto)
            <div class="col-md-6 col-lg-4">
                <article class="card h-100">
                    <div class="card-body">
                        <h2 class="h5">{{ $producto->nombre }}</h2>
                        <p>{{ $producto->descripcion }}</p>
                        <p class="mb-1"><strong>${{ number_format((float) $producto->precio, 2) }}</strong></p>
                        <p class="text-muted">Existencia: {{ $producto->existencia }} · Vendedor: {{ $producto->usuario->nombre }}</p>
                        <div class="mb-3">
                            @foreach($producto->categorias as $categoria)
                                <span class="badge text-bg-secondary">{{ $categoria->nombre }}</span>
                            @endforeach
                        </div>
                        @if(auth()->user()->esCliente() && $producto->existencia > 0)
                            <form method="POST" action="{{ route('ventas.store') }}">
                                @csrf
                                <input type="hidden" name="producto_id" value="{{ $producto->id }}">
                                <input type="hidden" name="fecha" value="{{ now()->toDateString() }}">
                                <button class="btn btn-primary" type="submit">Comprar</button>
                            </form>
                        @elseif($producto->existencia < 1)
                            <span class="badge text-bg-secondary">Agotado</span>
                        @endif
                        @can('update', $producto)
                            <a href="{{ route('productos.edit', $producto) }}" class="btn btn-outline-primary btn-sm">Editar</a>
                        @endcan
                        @can('delete', $producto)
                            <form method="POST" action="{{ route('productos.destroy', $producto) }}" class="d-inline"
                                  onsubmit="return confirm('¿Eliminar este producto?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-outline-danger btn-sm" type="submit">Eliminar</button>
                            </form>
                        @endcan
                    </div>
                </article>
            </div>
        @empty
            <p class="text-muted">Aún no hay productos disponibles.</p>
        @endforelse
    </div>
    <div class="mt-3">{{ $productos->links('pagination::bootstrap-5') }}</div>
@endsection
