@extends('layouts.app')
@section('titulo', 'Categorías')
@section('contenido')
    <div class="d-flex justify-content-between align-items-end mb-4">
        <header class="page-heading mb-0"><span class="eyebrow">Catálogo</span><h1 class="mb-0">Categorías</h1></header>
        @can('create', \App\Models\Categoria::class)
            <a href="{{ route('categorias.create') }}" class="btn btn-primary">+ Nueva categoría</a>
        @endcan
    </div>
    <div class="card"><div class="table-responsive"><table class="table table-hover mb-0">
        <thead><tr><th>Nombre</th><th>Descripción</th><th>Productos</th><th>Acciones</th></tr></thead><tbody>
        @forelse($categorias as $categoria)
            <tr><td>{{ $categoria->nombre }}</td><td>{{ $categoria->descripcion }}</td><td>{{ $categoria->productos_count }}</td>
                <td>@can('update', $categoria)
                    <a href="{{ route('categorias.edit', $categoria) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                    <form method="POST" action="{{ route('categorias.destroy', $categoria) }}" class="d-inline" onsubmit="return confirm('¿Eliminar esta categoría?')">
                        @csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Eliminar</button>
                    </form>
                @endcan</td>
            </tr>
        @empty <tr><td colspan="4" class="text-center">No hay categorías registradas.</td></tr>
        @endforelse
        </tbody></table></div></div>
    <div class="mt-3">{{ $categorias->links('pagination::bootstrap-5') }}</div>
@endsection
