<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    public const ROL_ADMINISTRADOR = 'administrador';

    public const ROL_GERENTE = 'gerente';

    public const ROL_CLIENTE = 'cliente';

    public const ROLES = [
        self::ROL_ADMINISTRADOR,
        self::ROL_GERENTE,
        self::ROL_CLIENTE,
    ];

    protected $fillable = [
        'nombre',
        'apellidos',
        'correo',
        'clave',
        'rol',
    ];

    protected $hidden = [
        'clave',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'clave' => 'hashed',
            'email_verified_at' => 'datetime',
        ];
    }

    public function getAuthPasswordName(): string
    {
        return 'clave';
    }

    public function getAuthPassword(): string
    {
        return $this->clave;
    }

    public function productos(): HasMany
    {
        return $this->hasMany(Producto::class, 'usuario_id');
    }

    public function ventasComoCliente(): HasMany
    {
        return $this->hasMany(Venta::class, 'cliente_id');
    }

    public function ventasComoVendedor(): HasMany
    {
        return $this->hasMany(Venta::class, 'vendedor_id');
    }

    public function categorias(): HasManyThrough
    {
        $relacion = $this->hasManyThrough(
            Categoria::class,
            Producto::class,
            'usuario_id',
            'id',
            'id',
            'id',
        );

        $consulta = $relacion->getQuery();
        $consulta->getQuery()->joins = [];
        $consulta->join('categoria_producto', 'categoria_producto.categoria_id', '=', 'categorias.id')
            ->join('productos', 'categoria_producto.producto_id', '=', 'productos.id')
            ->distinct();

        return $relacion;
    }

    public function esAdministrador(): bool
    {
        return $this->rol === self::ROL_ADMINISTRADOR;
    }

    public function esGerente(): bool
    {
        return $this->rol === self::ROL_GERENTE;
    }

    public function esCliente(): bool
    {
        return $this->rol === self::ROL_CLIENTE;
    }

    public function rutaDashboard(): string
    {
        return match ($this->rol) {
            self::ROL_ADMINISTRADOR => 'dashboard.administrador',
            self::ROL_GERENTE => 'dashboard.gerente',
            default => 'dashboard.cliente',
        };
    }
}
