<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetalleTraslado extends Model
{
    protected $table = "detalle_traslados";
    protected $fillable = ["traslado_id", "producto_id", "cantidad"];
    protected $casts = ["cantidad" => "decimal:2"];

    public function traslado(): BelongsTo { return $this->belongsTo(TrasladoStock::class, "traslado_id"); }
    public function producto(): BelongsTo { return $this->belongsTo(Producto::class); }
}
