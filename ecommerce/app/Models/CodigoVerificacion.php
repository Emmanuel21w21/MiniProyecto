<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CodigoVerificacion extends Model
{
    protected $table = 'codigos_verificacion';

    protected $fillable = [
        'usuario_id',
        'codigo',
        'fecha_expiracion',
        'usado',
    ];

    protected function casts(): array
    {
        return [
            'fecha_expiracion' => 'datetime',
            'usado' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $codigoVerificacion) {
            if ($codigoVerificacion->fecha_expiracion === null) {
                $codigoVerificacion->fecha_expiracion = now()->addMinutes(5);
            }
        });
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
