<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class RecepcionCompra extends Model
{
    protected $table = 'recepciones_compra';
    protected $fillable = ['pedido_compra_id','usuario_id','fecha_recepcion','numero_referencia','notas'];
    protected $casts = ['fecha_recepcion'=>'date'];
    public function pedidoCompra(): BelongsTo { return $this->belongsTo(PedidoCompra::class); }
    public function usuario(): BelongsTo { return $this->belongsTo(\App\Models\User::class); }
    public function detalles(): HasMany { return $this->hasMany(DetalleRecepcion::class, 'recepcion_id'); }
}