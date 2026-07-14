<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MovimientoContable extends Model
{
    protected $table = 'movimientos_contables';

    protected $fillable = ['asiento_id', 'cuenta_contable_id', 'debe', 'haber', 'descripcion'];

    protected $casts = ['debe' => 'decimal:2', 'haber' => 'decimal:2'];

    public function asiento(): BelongsTo
    {
        return $this->belongsTo(AsientoContable::class, 'asiento_id');
    }

    public function cuenta(): BelongsTo
    {
        return $this->belongsTo(CuentaContable::class, 'cuenta_contable_id');
    }
}
