@extends('layouts.app')
@section('titulo', 'Editar venta')
@section('contenido')
    <h1 class="h2 mb-4">Editar venta #{{ $venta->id }}</h1>
    <form method="POST" action="{{ route('ventas.update', $venta) }}" class="card card-body">
        @csrf @method('PUT')
        <div class="mb-3"><label for="producto_id" class="form-label">Producto</label>
            <select id="producto_id" name="producto_id" class="form-select">
                @foreach($productos as $producto)<option value="{{ $producto->id }}" @selected(old('producto_id', $venta->producto_id) == $producto->id)>{{ $producto->nombre }}</option>@endforeach
            </select>
        </div>
        <div class="mb-3"><label for="cliente_id" class="form-label">Cliente</label>
            <select id="cliente_id" name="cliente_id" class="form-select">
                @foreach($usuarios->where('rol', \App\Models\Usuario::ROL_CLIENTE) as $usuario)<option value="{{ $usuario->id }}" @selected(old('cliente_id', $venta->cliente_id) == $usuario->id)>{{ $usuario->nombre }} {{ $usuario->apellidos }}</option>@endforeach
            </select>
        </div>
        <div class="mb-3"><label for="fecha" class="form-label">Fecha</label><input id="fecha" type="date" name="fecha" value="{{ old('fecha', $venta->fecha->toDateString()) }}" class="form-control"></div>
        <div class="mb-3"><label for="total" class="form-label">Total</label><input id="total" type="number" step="0.01" min="0.01" name="total" value="{{ old('total', $venta->total) }}" class="form-control"></div>
        <div><button class="btn btn-primary">Guardar cambios</button> <a href="{{ route('ventas.index') }}" class="btn btn-link">Cancelar</a></div>
    </form>
@endsection
