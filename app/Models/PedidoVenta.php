<?php

namespace App\Models;

use App\Traits\PerteneceAEmpresa;
use App\Traits\RestringidoPorSucursal;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class PedidoVenta extends Model
{
    use PerteneceAEmpresa;
    use RestringidoPorSucursal;
    use LogsActivity;

    protected $table = 'pedidos_venta';

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['numero_referencia', 'total', 'monto_pagado', 'estado', 'estado_factura'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('pedidos_venta');
    }

    protected $fillable = [
        'cliente_id', 'usuario_id', 'ubicacion_id', 'termino_pago_id',
        'numero_referencia', 'referencia_cliente', 'comentarios',
        'fecha_pedido', 'fecha_entrega', 'direccion_entrega',
        'telefono_contacto', 'email_contacto',
        'total', 'monto_pagado', 'estado_factura', 'estado',
    ];

    protected $casts = [
        'fecha_pedido' => 'date',
        'fecha_entrega' => 'date',
        'total' => 'decimal:2',
        'monto_pagado' => 'decimal:2',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function ubicacion(): BelongsTo
    {
        return $this->belongsTo(Ubicacion::class);
    }

    public function terminoPago(): BelongsTo
    {
        return $this->belongsTo(TerminoPago::class);
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(DetallePedidoVenta::class, 'pedido_id');
    }

    public function facturas(): HasMany
    {
        return $this->hasMany(Factura::class, 'pedido_id');
    }

    public function pagos(): HasMany
    {
        return $this->hasMany(Pago::class, 'pedido_id');
    }

    public function envios(): HasMany
    {
        return $this->hasMany(Envio::class, 'pedido_id');
    }

    public function getSaldoPendienteAttribute(): float
    {
        return (float) ($this->total - $this->monto_pagado);
    }
}
