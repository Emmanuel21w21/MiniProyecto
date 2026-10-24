@extends('layouts.app')
@section('titulo', 'Editar producto')
@section('contenido')
    <h1 class="h2 mb-4">Editar producto</h1>
    <form method="POST" action="{{ route('productos.update', $producto) }}" class="card card-body">
        @csrf @method('PUT') @include('productos._form')
        <div><button class="btn btn-primary">Guardar cambios</button> <a href="{{ route('productos.index') }}" class="btn btn-link">Cancelar</a></div>
    </form>
@endsection
