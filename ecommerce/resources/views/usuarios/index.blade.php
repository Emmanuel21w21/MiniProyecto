@extends('layouts.app')

@section('titulo', 'Usuarios')

@section('contenido')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="mb-0">Usuarios</h1>
        <a href="{{ route('usuarios.create') }}" class="btn btn-primary">+ Nuevo usuario</a>
    </div>

    {{-- Búsqueda y filtro por rol --}}
    <form method="GET" action="{{ route('usuarios.index') }}" class="row g-2 mb-3">
        <div class="col-md-6">
            <input type="text" name="buscar" value="{{ request('buscar') }}" class="form-control"
                   placeholder="Buscar por nombre o correo">
        </div>
        <div class="col-md-3">
            <select name="rol" class="form-select">
                <option value="">Todos los roles</option>
                @foreach($roles as $rol)
                    <option value="{{ $rol }}" @selected(request('rol') === $rol)>{{ ucfirst($rol) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-outline-primary">Filtrar</button>
            <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">Limpiar</a>
        </div>
    </form>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Rol</th>
                        <th>Registrado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($usuarios as $usuario)
                        <tr>
                            <td>{{ $usuario->id }}</td>
                            <td>{{ $usuario->name }}</td>
                            <td>{{ $usuario->email }}</td>
                            <td>
                                @php
                                    $color = match($usuario->rol) {
                                        'gerente' => 'danger',
                                        'empleado' => 'warning',
                                        default => 'info',
                                    };
                                @endphp
                                <span class="badge text-bg-{{ $color }} text-capitalize">{{ $usuario->rol }}</span>
                            </td>
                            <td>{{ $usuario->created_at?->format('d/m/Y') }}</td>
                            <td class="text-end">
                                <a href="{{ route('usuarios.edit', $usuario) }}" class="btn btn-sm btn-outline-primary">Editar</a>

                                @if($usuario->id !== auth()->id())
                                    <form method="POST" action="{{ route('usuarios.destroy', $usuario) }}" class="d-inline"
                                          onsubmit="return confirm('¿Eliminar a {{ $usuario->name }}? Esta acción no se puede deshacer.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Eliminar</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No se encontraron usuarios.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $usuarios->links('pagination::bootstrap-5') }}
    </div>
@endsection
