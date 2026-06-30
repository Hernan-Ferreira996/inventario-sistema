<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetallePresupuesto extends Model
{
    protected $table = "detalle_presupuestos";
    protected $fillable = ["presupuesto_id", "producto_id", "cantidad", "precio_unitario", "descuento", "impuesto", "subtotal"];
    protected $casts = [
        "cantidad" => "decimal:2", "precio_unitario" => "decimal:2",
        "descuento" => "decimal:2", "impuesto" => "decimal:2", "subtotal" => "decimal:2",
    ];

    public function presupuesto(): BelongsTo { return $this->belongsTo(Presupuesto::class); }
    public function producto(): BelongsTo { return $this->belongsTo(Producto::class); }
}
