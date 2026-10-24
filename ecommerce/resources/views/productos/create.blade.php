@extends('layouts.app')
@section('titulo', 'Crear producto')
@section('contenido')
    <h1 class="h2 mb-4">Nuevo producto</h1>
    <form method="POST" action="{{ route('productos.store') }}" enctype="multipart/form-data" class="card card-body">
        @csrf @include('productos._form')
        <div><button class="btn btn-primary">Guardar</button> <a href="{{ route('productos.index') }}" class="btn btn-link">Cancelar</a></div>
    </form>
@endsection
