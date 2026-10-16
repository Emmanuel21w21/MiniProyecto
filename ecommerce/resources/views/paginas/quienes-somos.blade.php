@extends('layouts.app')

@section('titulo', 'Quiénes somos')

@section('contenido')
    <header class="page-heading">
        <span class="eyebrow">Nuestra historia</span>
        <h1>Quiénes somos</h1>
        <p class="mb-0">Conoce al equipo y los principios detrás de esta tienda.</p>
    </header>
    <div class="card info-card">
        <div class="card-body">
            <p>
                Somos una tienda en línea creada por estudiantes como proyecto académico. Nuestro objetivo es ofrecer
                una experiencia de compra sencilla, segura y transparente, con atención personalizada para cada cliente.
            </p>
            <p>
                Nuestro equipo está formado por clientes, empleados y gerentes que trabajan en conjunto desde la
                misma plataforma, cada quien con las herramientas que necesita para su función.
            </p>
            <p class="mb-0 text-muted">
                <!-- TODO: reemplaza este texto con la descripción real de tu empresa y los nombres de tu equipo. -->
                Fundada en 2026.
            </p>
        </div>
    </div>
@endsection
