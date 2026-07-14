<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Interaccion extends Model
{
    protected $table = 'interacciones';

    protected $fillable = ['empresa_id', 'usuario_id', 'tipo', 'fecha', 'descripcion'];

    protected $casts = ['fecha' => 'datetime'];

    public function interactuable(): MorphTo
    {
        return $this->morphTo();
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
