<?php

namespace App\Models;

use App\Traits\PerteneceAEmpresa;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CentroCosto extends Model
{
    use PerteneceAEmpresa;

    protected $table = 'centros_costo';
    protected $guarded = [];
    protected $casts = ['activo' => 'boolean'];

    public function pedidosCompra(): HasMany
    {
        return $this->hasMany(PedidoCompra::class);
    }
}
