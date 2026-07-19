<?php

namespace App\Models;

use App\Traits\PerteneceAEmpresa;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Caja extends Model
{
    use PerteneceAEmpresa;

    protected $table = 'cajas';
    protected $guarded = [];
    protected $casts = ['activo' => 'boolean'];

    public function pagos(): HasMany
    {
        return $this->hasMany(Pago::class);
    }

    public function cierres(): HasMany
    {
        return $this->hasMany(CierreCaja::class);
    }
}
