@extends('layouts.app')

@section('titulo', 'Ubicación')

@section('contenido')
    <header class="page-heading">
        <span class="eyebrow">Visítanos</span>
        <h1>Ubicación</h1>
        <p class="mb-0">Nos encantará recibirte en nuestra tienda.</p>
    </header>
    <div class="card info-card">
        <div class="card-body">
            <p class="mb-3"><strong>Dirección</strong><br>Calle Ejemplo 123, Col. Centro, Guadalupe, Nuevo León, México</p>
            <p class="mb-3"><strong>Horario</strong><br>Lunes a viernes de 9:00 a 18:00 h</p>
            <p class="text-muted mb-0">
                <!-- TODO: reemplaza la dirección con la real y, si quieres, agrega un iframe de Google Maps. -->
                Atención presencial previa cita.
            </p>
        </div>
    </div>
@endsection
