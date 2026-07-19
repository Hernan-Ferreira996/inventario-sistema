<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CuotaFacturaProveedor extends Model
{
    protected $table = 'cuotas_factura_proveedor';
    protected $guarded = [];
    protected $casts = [
        'fecha_vencimiento' => 'date',
        'monto' => 'decimal:2',
        'pagada' => 'boolean',
        'fecha_pago' => 'date',
    ];

    public function facturaProveedor(): BelongsTo { return $this->belongsTo(FacturaProveedor::class); }
}
