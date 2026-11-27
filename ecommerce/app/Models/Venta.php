<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Venta extends Model
{
    use HasFactory;

    protected $fillable = [
        'producto_id',
        'vendedor_id',
        'cliente_id',
        'fecha',
        'total',
        'estado',
        'fecha_validacion',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
            'total' => 'decimal:2',
            'fecha_validacion' => 'datetime',
        ];
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'cliente_id');
    }

    public function vendedor(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'vendedor_id');
    }
}
