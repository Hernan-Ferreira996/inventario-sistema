<?php

namespace App\Traits;

use App\Models\Contacto;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait TieneContactos
{
    public function contactos(): MorphMany
    {
        return $this->morphMany(Contacto::class, 'contactable')->orderByDesc('es_principal')->orderBy('nombre');
    }
}
