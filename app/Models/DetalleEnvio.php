<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class DetalleEnvio extends Model
{
    protected $table = "detalle_envios";
    protected $fillable = ["envio_id","producto_id","cantidad"];
    protected $casts = ["cantidad"=>"decimal:2"];
    public function envio(): BelongsTo { return $this->belongsTo(Envio::class); }
    public function producto(): BelongsTo { return $this->belongsTo(Producto::class); }
}