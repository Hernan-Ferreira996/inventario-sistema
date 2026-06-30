<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class DetallePedidoCompra extends Model
{
    protected $table = 'detalle_pedidos_compra';
    protected $fillable = ['pedido_compra_id','producto_id','cantidad','precio_unitario','subtotal','cantidad_recibida'];
    protected $casts = ['cantidad'=>'decimal:2','precio_unitario'=>'decimal:2','subtotal'=>'decimal:2','cantidad_recibida'=>'decimal:2'];
    public function pedidoCompra(): BelongsTo { return $this->belongsTo(PedidoCompra::class); }
    public function producto(): BelongsTo { return $this->belongsTo(Producto::class); }
}