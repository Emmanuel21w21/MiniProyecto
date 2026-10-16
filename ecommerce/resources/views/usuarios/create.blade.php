@extends('layouts.app')

@section('titulo', 'Nuevo usuario')

@section('contenido')
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <header class="page-heading">
                <span class="eyebrow">Administración</span>
                <h1>Nuevo usuario</h1>
                <p class="mb-0">Completa los datos para dar de alta un usuario.</p>
            </header>
            <div class="card">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('usuarios.store') }}" novalidate>
                        @csrf
                        @include('usuarios._form')
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Guardar</button>
                            <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
