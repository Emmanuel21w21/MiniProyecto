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

    public function assignRole(Usuario $actor, string $rol): bool
    {
        return $actor->esAdministrador()
            || ($actor->esGerente() && $rol === Usuario::ROL_CLIENTE);
    }

    public function viewDashboard(Usuario $actor, string $rol): bool
    {
        return match ($rol) {
            Usuario::ROL_ADMINISTRADOR => $actor->esAdministrador(),
            Usuario::ROL_GERENTE => $actor->esGerente(),
            Usuario::ROL_CLIENTE => $actor->esCliente(),
            default => false,
        };
    }
}
