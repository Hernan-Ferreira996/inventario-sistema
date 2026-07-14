<?php

namespace App\Models;

use App\Traits\PerteneceAEmpresa;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CuentaContable extends Model
{
    use PerteneceAEmpresa;

    protected $table = 'cuentas_contables';

    protected $fillable = ['cuenta_padre_id', 'codigo', 'nombre', 'tipo', 'naturaleza', 'imputable', 'activo'];

    protected $casts = ['imputable' => 'boolean', 'activo' => 'boolean'];

    public function padre(): BelongsTo
    {
        return $this->belongsTo(self::class, 'cuenta_padre_id');
    }

    public function hijas(): HasMany
    {
        return $this->hasMany(self::class, 'cuenta_padre_id');
    }

    public function movimientos(): HasMany
    {
        return $this->hasMany(MovimientoContable::class);
    }

    /**
     * Saldo = suma(debe) - suma(haber) si es cuenta deudora (activo/gasto),
     * o al revés si es acreedora (pasivo/patrimonio/ingreso).
     */
    public function getSaldoAttribute(): float
    {
        $debe = (float) $this->movimientos()->sum('debe');
        $haber = (float) $this->movimientos()->sum('haber');
        return $this->naturaleza === 'deudora' ? $debe - $haber : $haber - $debe;
    }
}
