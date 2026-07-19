<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ciudad extends Model
{
    protected $table = 'ciudades';
    protected $guarded = [];
    protected $casts = ['activo' => 'boolean'];

    public function clientes(): HasMany
    {
        return $this->hasMany(Cliente::class);
    }

    public function proveedores(): HasMany
    {
        return $this->hasMany(Proveedor::class);
    }

    public function getNombreCompletoAttribute(): string
    {
        return $this->departamento ? "{$this->nombre}, {$this->departamento}" : $this->nombre;
    }
}
