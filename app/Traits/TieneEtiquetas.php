<?php

namespace App\Traits;

use App\Models\Etiqueta;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait TieneEtiquetas
{
    public function etiquetas(): MorphToMany
    {
        return $this->morphToMany(Etiqueta::class, 'etiquetable');
    }

    /**
     * Sincroniza etiquetas a partir de nombres libres (separados por coma),
     * creando las que no existan para la empresa actual.
     */
    public function sincronizarEtiquetas(?string $nombresSeparadosPorComa): void
    {
        $nombres = array_values(array_filter(array_map('trim', explode(',', $nombresSeparadosPorComa ?? ''))));

        $ids = collect($nombres)->map(function ($nombre) {
            return Etiqueta::firstOrCreate(
                ['empresa_id' => $this->empresa_id, 'nombre' => $nombre]
            )->id;
        });

        $this->etiquetas()->sync($ids);
    }
}
