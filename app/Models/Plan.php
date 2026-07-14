<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    protected $table = 'planes';

    protected $fillable = [
        'codigo', 'nombre', 'descripcion', 'precio_mensual',
        'max_usuarios', 'max_sucursales', 'orden', 'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'precio_mensual' => 'decimal:2',
    ];

    public function modulos(): BelongsToMany
    {
        return $this->belongsToMany(Modulo::class, 'modulo_plan');
    }

    public function empresas(): HasMany
    {
        return $this->hasMany(Empresa::class);
    }
}
