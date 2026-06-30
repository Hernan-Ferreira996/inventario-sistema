<?php
namespace App\Models;

use App\Traits\PerteneceAEmpresa;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Factura extends Model
{
    use PerteneceAEmpresa;
    use LogsActivity;

    protected $table = "facturas";
    protected $fillable = [
        "pedido_id", "numero_factura", "fecha_factura",
        "timbrado", "establecimiento", "punto_expedicion", "cdc", "modo",
        "tipo_documento_cliente", "numero_documento_cliente", "condicion_venta",
        "subtotal", "impuesto_total", "total", "monto_pagado", "descuento_global", "monto_descuento", "estado", "notas",
    ];
    protected $casts = [
        "fecha_factura" => "date",
        "subtotal" => "decimal:2", "impuesto_total" => "decimal:2",
        "total" => "decimal:2", "monto_pagado" => "decimal:2",
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(["numero_factura", "total", "estado", "monto_pagado"])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName("facturas");
    }

    public function pedido(): BelongsTo { return $this->belongsTo(PedidoVenta::class, "pedido_id"); }
    public function pagos(): HasMany { return $this->hasMany(Pago::class); }
    public function notasCredito(): HasMany { return $this->hasMany(NotaCredito::class); }

    public function getSaldoPendienteAttribute(): float
    {
        return (float) ($this->total - $this->monto_pagado);
    }

    public function getNumeroDocumentoAttribute(): string
    {
        return "{$this->establecimiento}-{$this->punto_expedicion}-{$this->numero_factura}";
    }
}
