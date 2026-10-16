@extends('layouts.app')

@section('titulo', 'Contáctanos')

@section('contenido')
    <header class="page-heading">
        <span class="eyebrow">Estamos para ayudarte</span>
        <h1>Hablemos</h1>
        <p class="mb-0">Escríbenos o llámanos, con gusto te atenderemos.</p>
    </header>
    <div class="card info-card">
        <div class="card-body">
            <ul class="list-unstyled mb-0 d-grid gap-3">
                <li><strong>Correo</strong><br><a href="mailto:contacto@ecommerce.test">contacto@ecommerce.test</a></li>
                <li><strong>Teléfono</strong><br><a href="tel:+528100000000">(81) 0000 0000</a></li>
                <li><strong>Horario</strong><br>Lunes a viernes de 9:00 a 18:00 h</li>
            </ul>
        </div>
    </div>
@endsection
