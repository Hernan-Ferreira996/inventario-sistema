<?php

namespace App\Models;

use App\Models\AsientoContable;
use App\Traits\PerteneceAEmpresa;
use App\Traits\RestringidoPorSucursal;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class FacturaProveedor extends Model
{
    use PerteneceAEmpresa;
    use RestringidoPorSucursal;
    use SoftDeletes, LogsActivity;

    protected $table = 'facturas_proveedor';

    protected $fillable = [
        'proveedor_id', 'centro_costo_id', 'usuario_id',
        'numero_referencia', 'numero_factura_proveedor', 'timbrado_proveedor', 'ruc_proveedor',
        'fecha_emision', 'fecha_vencimiento',
        'subtotal', 'iva_total', 'total', 'monto_pagado',
        'retiene_iva', 'retencion_timbrado', 'retencion_numero', 'retencion_porcentaje', 'retencion_monto',
        'estado', 'observaciones',
    ];

    protected $casts = [
        'fecha_emision' => 'date',
        'fecha_vencimiento' => 'date',
        'subtotal' => 'decimal:2',
        'iva_total' => 'decimal:2',
        'total' => 'decimal:2',
        'monto_pagado' => 'decimal:2',
        'retiene_iva' => 'boolean',
        'retencion_porcentaje' => 'decimal:2',
        'retencion_monto' => 'decimal:2',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['numero_factura_proveedor', 'total', 'estado'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('facturas_proveedor');
    }

    protected static function booted(): void
    {
        static::deleting(function (self $factura) {
            $asiento = AsientoContable::buscarPorOrigen($factura);
            if ($asiento) {
                AsientoContable::revertir($asiento, "Factura de Proveedor {$factura->numero_referencia} eliminada");
            }
        });
    }

    public function proveedor(): BelongsTo { return $this->belongsTo(Proveedor::class); }
    public function centroCosto(): BelongsTo { return $this->belongsTo(CentroCosto::class); }
    public function usuario(): BelongsTo { return $this->belongsTo(User::class); }
    public function detalles(): HasMany { return $this->hasMany(DetalleFacturaProveedor::class); }
    public function cuotas(): HasMany { return $this->hasMany(CuotaFacturaProveedor::class); }

    public function getSaldoPendienteAttribute(): float
    {
        return (float) $this->total - (float) $this->monto_pagado;
    }
}
