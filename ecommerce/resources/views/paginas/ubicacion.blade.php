@extends('layouts.app')

@section('titulo', 'Ubicación')

@section('contenido')
    <h1 class="mb-3">Ubicación</h1>
    <div class="card shadow-sm">
        <div class="card-body">
            <p class="mb-1"><strong>Dirección:</strong> Calle Ejemplo 123, Col. Centro, Guadalupe, Nuevo León, México</p>
            <p class="mb-1"><strong>Horario:</strong> Lunes a viernes de 9:00 a 18:00 h</p>
            <p class="text-muted mb-0">
                <!-- TODO: reemplaza la dirección con la real y, si quieres, agrega un iframe de Google Maps. -->
                Atención presencial previa cita.
            </p>
        </div>
    </div>
@endsection
