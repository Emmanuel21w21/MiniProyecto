<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const ROL_CLIENTE = 'cliente';
    public const ROL_EMPLEADO = 'empleado';
    public const ROL_GERENTE = 'gerente';

    public const ROLES = [
        self::ROL_CLIENTE,
        self::ROL_EMPLEADO,
        self::ROL_GERENTE,
    ];

    protected $fillable = [
        'name',
        'email',
        'password',
        'rol',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function esCliente(): bool
    {
        return $this->rol === self::ROL_CLIENTE;
    }

    public function esEmpleado(): bool
    {
        return $this->rol === self::ROL_EMPLEADO;
    }

    public function esGerente(): bool
    {
        return $this->rol === self::ROL_GERENTE;
    }

    /**
     * Nombre de la ruta del dashboard que le corresponde al usuario según su rol.
     */
    public function rutaDashboard(): string
    {
        return match ($this->rol) {
            self::ROL_GERENTE => 'dashboard.gerente',
            self::ROL_EMPLEADO => 'dashboard.empleado',
            default => 'dashboard.cliente',
        };
    }
}
