<?php

namespace App\Traits;

use App\Models\Interaccion;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait TieneInteracciones
{
    public function interacciones(): MorphMany
    {
        return $this->morphMany(Interaccion::class, 'interactuable')->latest('fecha');
    }
}
