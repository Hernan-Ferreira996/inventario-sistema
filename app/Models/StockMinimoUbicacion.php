<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMinimoUbicacion extends Model
{
    protected $table = 'stock_minimos_ubicacion';
    protected $guarded = [];
    protected $casts = ['cantidad_minima' => 'decimal:2'];

    public function producto(): BelongsTo { return $this->belongsTo(Producto::class); }
    public function ubicacion(): BelongsTo { return $this->belongsTo(Ubicacion::class); }
}
