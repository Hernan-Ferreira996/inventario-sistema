<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Etiqueta extends Model
{
    protected $table = 'etiquetas';

    protected $fillable = ['empresa_id', 'nombre', 'color'];

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }
}
