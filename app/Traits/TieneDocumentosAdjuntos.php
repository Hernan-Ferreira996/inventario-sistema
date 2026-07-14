<?php

namespace App\Traits;

use App\Models\DocumentoAdjunto;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait TieneDocumentosAdjuntos
{
    public function documentosAdjuntos(): MorphMany
    {
        return $this->morphMany(DocumentoAdjunto::class, 'adjuntable')->latest();
    }
}
