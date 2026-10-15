@extends('layouts.app')

@section('titulo', 'Editar usuario')

@section('contenido')
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <h1 class="mb-3">Editar usuario</h1>
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('usuarios.update', $usuario) }}" novalidate>
                        @csrf
                        @method('PUT')
                        @include('usuarios._form')
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Actualizar</button>
                            <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
