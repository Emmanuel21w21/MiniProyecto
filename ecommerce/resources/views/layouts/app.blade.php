<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('titulo', 'XGAMESHOP') | {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/gamer-theme.css') }}?v={{ filemtime(public_path('css/gamer-theme.css')) }}" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite('resources/js/app.js')
    @endif
</head>
<body class="d-flex flex-column min-vh-100">

<nav class="navbar navbar-expand-lg navbar-dark site-nav">
    <div class="container">
        <a class="navbar-brand fw-semibold" href="{{ route('inicio') }}">
            <span class="brand-mark" aria-hidden="true">🎮</span>
            <span>XGAMESHOP</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu" aria-controls="menu" aria-expanded="false" aria-label="Abrir menú">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menu">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link @if(request()->routeIs('inicio')) active @endif" href="{{ route('inicio') }}">Inicio</a></li>
                <li class="nav-item"><a class="nav-link @if(request()->routeIs('quienes-somos')) active @endif" href="{{ route('quienes-somos') }}">Quiénes somos</a></li>
                <li class="nav-item"><a class="nav-link @if(request()->routeIs('mision-vision')) active @endif" href="{{ route('mision-vision') }}">Misión y visión</a></li>
                <li class="nav-item"><a class="nav-link @if(request()->routeIs('ubicacion')) active @endif" href="{{ route('ubicacion') }}">Ubicación</a></li>
                <li class="nav-item"><a class="nav-link @if(request()->routeIs('contacto')) active @endif" href="{{ route('contacto') }}">Contáctanos</a></li>
                @auth
                    <li class="nav-item"><a class="nav-link" href="{{ route('productos.index') }}">Productos</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('categorias.index') }}">Categorías</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('ventas.index') }}">Ventas</a></li>
                @endauth
            </ul>

            <ul class="navbar-nav align-items-lg-center gap-lg-2">
                @guest
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Iniciar sesión</a></li>
                    <li class="nav-item"><a class="btn btn-primary btn-sm" href="{{ route('registro') }}">Registrarse</a></li>
                @endguest

                @auth
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">
                            Mi panel <span class="badge text-bg-secondary text-capitalize">{{ auth()->user()->rol }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-light btn-sm">Cerrar sesión</button>
                        </form>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<main class="flex-grow-1 site-main">
    <div class="container py-4">
        @if(session('exito'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('exito') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('contenido')
    </div>
</main>

<footer class="text-center site-footer">
    <small>&copy; {{ date('Y') }} XGAMESHOP · Proyecto académico</small>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
