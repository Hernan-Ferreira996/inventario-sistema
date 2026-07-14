<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class DocumentoAdjunto extends Model
{
    protected $table = 'documentos_adjuntos';

    protected $fillable = ['empresa_id', 'usuario_id', 'nombre_archivo', 'ruta', 'tipo_mime', 'tamano'];

    public function adjuntable(): MorphTo
    {
        return $this->morphTo();
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
