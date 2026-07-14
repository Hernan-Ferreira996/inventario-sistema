<?php

namespace App\Traits;

use App\Models\CampoPersonalizado;
use App\Models\ValorCampoPersonalizado;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait TieneCamposPersonalizados
{
    public function valoresCamposPersonalizados(): MorphMany
    {
        return $this->morphMany(ValorCampoPersonalizado::class, 'valorable');
    }

    /**
     * Definiciones de campos personalizados activos para esta entidad (nombre de tabla/modelo).
     */
    public function camposPersonalizadosDisponibles(): \Illuminate\Support\Collection
    {
        return CampoPersonalizado::paraEntidad(static::entidadCamposPersonalizados(), $this->empresa_id);
    }

    /**
     * Valores actuales indexados por nombre de campo, listos para prellenar un formulario.
     */
    public function valoresCamposPersonalizadosPorNombre(): array
    {
        $valores = $this->valoresCamposPersonalizados()->with('campo')->get();
        $resultado = [];
        foreach ($valores as $v) {
            if ($v->campo) {
                $resultado[$v->campo->nombre] = $v->valor;
            }
        }
        return $resultado;
    }

    /**
     * Guarda/actualiza los valores recibidos (array nombre_campo => valor) para los
     * campos personalizados activos de esta entidad.
     */
    public function guardarCamposPersonalizados(array $valoresPorNombre): void
    {
        $campos = $this->camposPersonalizadosDisponibles()->keyBy('nombre');

        foreach ($valoresPorNombre as $nombre => $valor) {
            $campo = $campos->get($nombre);
            if (!$campo) {
                continue;
            }
            ValorCampoPersonalizado::updateOrCreate(
                ['campo_id' => $campo->id, 'valorable_type' => static::class, 'valorable_id' => $this->id],
                ['valor' => $valor]
            );
        }
    }

    abstract public static function entidadCamposPersonalizados(): string;
}
