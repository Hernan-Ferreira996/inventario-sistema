<?php

namespace App\Models;

use App\Traits\PerteneceAEmpresa;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ubicacion extends Model
{
    use PerteneceAEmpresa;
    protected $table = 'ubicaciones';
    protected $guarded = [];

    public function sucursal(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class);
    }

    /**
     * Filtra a las ubicaciones que $user tiene permitido operar. Si el
     * usuario no tiene ubicaciones explícitamente asignadas (tabla
     * usuario_ubicacion), no se restringe nada — mismo criterio que
     * User::tieneSucursalesRestringidas(), para no romper el acceso de
     * usuarios existentes hasta que un admin asigne depósitos puntuales.
     */
    public function scopeVisiblesPara($query, User $user)
    {
        if ($user->esSuperAdmin() || !$user->tieneUbicacionesRestringidas()) {
            return $query;
        }

        return $query->whereIn('ubicaciones.id', $user->ubicaciones()->pluck('ubicaciones.id'));
    }

    /**
     * Aborta con 403 si $user no tiene permitido operar sobre esta ubicación.
     * Usar en toda acción que registre movimiento de stock, para que la
     * restricción no dependa solo de ocultar opciones en el formulario.
     */
    public static function verificarAcceso(User $user, int $ubicacionId): void
    {
        $permitida = static::query()->whereKey($ubicacionId)->visiblesPara($user)->exists();

        abort_unless($permitida, 403, 'No tenés permiso para operar sobre este depósito.');
    }
}
