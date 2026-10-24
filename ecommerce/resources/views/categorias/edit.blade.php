@extends('layouts.app')
@section('titulo', 'Editar categoría')
@section('contenido')
    <h1 class="h2 mb-4">Editar categoría</h1>
    <form method="POST" action="{{ route('categorias.update', $categoria) }}" class="card card-body">
        @csrf @method('PUT') @include('categorias._form')
        <div><button class="btn btn-primary">Guardar cambios</button> <a href="{{ route('categorias.index') }}" class="btn btn-link">Cancelar</a></div>
    </form>
@endsection
