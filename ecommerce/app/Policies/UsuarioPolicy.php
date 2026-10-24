<?php

namespace App\Policies;

use App\Models\Usuario;

class UsuarioPolicy
{
    public function viewAny(Usuario $actor): bool
    {
        return $actor->esAdministrador() || $actor->esGerente();
    }

    public function create(Usuario $actor): bool
    {
        return $actor->esAdministrador();
    }

    public function update(Usuario $actor, Usuario $usuario): bool
    {
        return $actor->esAdministrador()
            || ($actor->esGerente() && $usuario->esCliente());
    }

    public function delete(Usuario $actor, Usuario $usuario): bool
    {
        return $actor->esAdministrador()
            && $actor->isNot($usuario)
            && $usuario->ventasComoCliente()->doesntExist()
            && $usuario->ventasComoVendedor()->doesntExist();
    }
}
