<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class DetalleRecepcion extends Model
{
    protected $table = 'detalle_recepciones';
    protected $fillable = ['recepcion_id','producto_id','ubicacion_id','cantidad'];
    protected $casts = ['cantidad'=>'decimal:2'];
    public function recepcion(): BelongsTo { return $this->belongsTo(RecepcionCompra::class); }
    public function producto(): BelongsTo { return $this->belongsTo(Producto::class); }
    public function ubicacion(): BelongsTo { return $this->belongsTo(Ubicacion::class); }
}