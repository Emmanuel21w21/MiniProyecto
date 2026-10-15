<?php

namespace App\Http\Controllers;

/**
 * Páginas estáticas públicas, visibles para cualquier visitante.
 */
class PaginaController extends Controller
{
    public function inicio()
    {
        return view('paginas.inicio');
    }

    public function quienesSomos()
    {
        return view('paginas.quienes-somos');
    }

    public function misionVision()
    {
        return view('paginas.mision-vision');
    }

    public function ubicacion()
    {
        return view('paginas.ubicacion');
    }

    public function contacto()
    {
        return view('paginas.contacto');
    }
}
