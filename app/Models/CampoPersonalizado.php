<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CampoPersonalizado extends Model
{
    protected $table = 'campos_personalizados';

    protected $fillable = [
        'empresa_id', 'entidad', 'nombre', 'etiqueta', 'tipo', 'opciones', 'requerido', 'orden', 'activo',
    ];

    protected $casts = [
        'opciones' => 'array',
        'requerido' => 'boolean',
        'activo' => 'boolean',
    ];

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    public function valores(): HasMany
    {
        return $this->hasMany(ValorCampoPersonalizado::class, 'campo_id');
    }

    public static function paraEntidad(string $entidad, ?int $empresaId = null): \Illuminate\Support\Collection
    {
        $empresaId ??= auth()->check() ? auth()->user()->empresa_id : null;

        return static::where('entidad', $entidad)
            ->where('empresa_id', $empresaId)
            ->where('activo', true)
            ->orderBy('orden')
            ->get();
    }
}
