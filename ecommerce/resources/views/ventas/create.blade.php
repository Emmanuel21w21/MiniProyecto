@extends('layouts.app')
@section('titulo', 'Comprar producto')
@section('contenido')
    <h1 class="h2 mb-4">Comprar producto</h1>
    <form method="POST" action="{{ route('ventas.store') }}" class="card card-body">
        @csrf
        <div class="mb-3">
            <label for="producto_id" class="form-label">Producto</label>
            <select id="producto_id" name="producto_id" class="form-select @error('producto_id') is-invalid @enderror" required>
                <option value="">Selecciona un producto</option>
                @foreach($productos as $producto)
                    <option value="{{ $producto->id }}" @selected(old('producto_id') == $producto->id)>
                        {{ $producto->nombre }} — ${{ number_format((float) $producto->precio, 2) }} ({{ $producto->existencia }} disponibles)
                    </option>
                @endforeach
            </select>
            @error('producto_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <input type="hidden" name="fecha" value="{{ now()->toDateString() }}">
        <div><button class="btn btn-primary">Confirmar compra</button> <a href="{{ route('productos.index') }}" class="btn btn-link">Cancelar</a></div>
    </form>
@endsection
