<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Modulo extends Model
{
    protected $table = 'modulos';

    protected $fillable = ['codigo', 'nombre', 'descripcion', 'icono', 'nucleo', 'orden', 'activo'];

    protected $casts = [
        'nucleo' => 'boolean',
        'activo' => 'boolean',
    ];

    public function planes(): BelongsToMany
    {
        return $this->belongsToMany(Plan::class, 'modulo_plan');
    }
}
