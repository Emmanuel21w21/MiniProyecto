<?php

namespace App\Policies;

use App\Models\Usuario;
use App\Models\Venta;

class VentaPolicy
{
    public function viewAny(Usuario $usuario): bool
    {
        return $usuario->esAdministrador()
            || $usuario->esGerente()
            || $usuario->esCliente();
    }

    public function view(Usuario $usuario, Venta $venta): bool
    {
        return $usuario->esAdministrador()
            || $usuario->esGerente()
            || ($usuario->esCliente()
                && ($venta->cliente_id === $usuario->id || $venta->vendedor_id === $usuario->id));
    }

    public function create(Usuario $usuario): bool
    {
        return $usuario->esCliente();
    }

    public function update(Usuario $usuario, Venta $venta): bool
    {
        return $usuario->esAdministrador();
    }

    public function delete(Usuario $usuario, Venta $venta): bool
    {
        return $usuario->esAdministrador();
    }
}
