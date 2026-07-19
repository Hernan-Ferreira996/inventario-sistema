<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetalleFacturaProveedor extends Model
{
    protected $table = 'detalle_facturas_proveedor';
    protected $guarded = [];
    protected $casts = [
        'cantidad' => 'decimal:2',
        'precio_unitario' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function facturaProveedor(): BelongsTo { return $this->belongsTo(FacturaProveedor::class); }
    public function centroCosto(): BelongsTo { return $this->belongsTo(CentroCosto::class); }
}
