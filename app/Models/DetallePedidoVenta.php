<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class DetallePedidoVenta extends Model
{
    protected $table = "detalle_pedidos_venta";
    protected $fillable = [
        "pedido_id","producto_id","cantidad","precio_unitario",
        "descuento","impuesto","subtotal","cantidad_enviada","cantidad_facturada"
    ];
    protected $casts = [
        "cantidad"=>"decimal:2","precio_unitario"=>"decimal:2",
        "descuento"=>"decimal:2","impuesto"=>"decimal:2","subtotal"=>"decimal:2",
        "cantidad_enviada"=>"decimal:2","cantidad_facturada"=>"decimal:2"
    ];
    public function pedido(): BelongsTo { return $this->belongsTo(PedidoVenta::class, "pedido_id"); }
    public function producto(): BelongsTo { return $this->belongsTo(Producto::class); }
}