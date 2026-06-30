<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetalleNotaRemision extends Model
{
    protected $table = "detalle_notas_remision";
    protected $fillable = ["nota_remision_id", "producto_id", "cantidad"];
    protected $casts = ["cantidad" => "decimal:2"];

    public function notaRemision(): BelongsTo { return $this->belongsTo(NotaRemision::class); }
    public function producto(): BelongsTo { return $this->belongsTo(Producto::class); }
}
