@extends('layouts.app')
@section('titulo', 'Crear categoría')
@section('contenido')
    <h1 class="h2 mb-4">Nueva categoría</h1>
    <form method="POST" action="{{ route('categorias.store') }}" class="card card-body">
        @csrf @include('categorias._form')
        <div><button class="btn btn-primary">Guardar</button> <a href="{{ route('categorias.index') }}" class="btn btn-link">Cancelar</a></div>
    </form>
@endsection
