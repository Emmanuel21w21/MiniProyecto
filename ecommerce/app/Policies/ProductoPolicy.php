<?php

namespace App\Policies;

use App\Models\Producto;
use App\Models\Usuario;
use App\Models\Venta;

class ProductoPolicy
{
    public function viewAny(Usuario $usuario): bool
    {
        return true;
    }

    public function view(Usuario $usuario, Producto $producto): bool
    {
        return true;
    }

    public function create(Usuario $usuario): bool
    {
        return $usuario->esAdministrador() || $usuario->esGerente();
    }

    public function update(Usuario $usuario, Producto $producto): bool
    {
        return $usuario->esAdministrador()
            || ($usuario->esGerente() && $producto->usuario_id === $usuario->id);
    }

    public function delete(Usuario $usuario, Producto $producto): bool
    {
        return $this->update($usuario, $producto)
            && $producto->ventas()->doesntExist();
    }

    public function purchase(Usuario $usuario, Producto $producto): bool
    {
        return $producto->existencia > 0
            && $usuario->can('create', Venta::class);
    }
}
