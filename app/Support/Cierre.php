<?php

namespace App\Support;

use App\Models\CierrePeriodo;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class Cierre
{
    /**
     * Fecha del último cierre de período registrado para la empresa (o null si nunca se cerró nada).
     * Todo documento con fecha <= esta fecha queda bloqueado para eliminar/anular.
     */
    public static function fechaVigente(?int $empresaId = null): ?Carbon
    {
        $empresaId ??= Auth::check() ? Auth::user()->empresa_id : null;

        $fecha = CierrePeriodo::where('empresa_id', $empresaId)->max('fecha_cierre');

        return $fecha ? Carbon::parse($fecha) : null;
    }

    public static function estaBloqueada($fecha, ?int $empresaId = null): bool
    {
        $vigente = self::fechaVigente($empresaId);

        return $vigente !== null && Carbon::parse($fecha)->lte($vigente);
    }

    /**
     * Mensaje de error estándar a mostrar cuando una operación queda bloqueada por el cierre.
     */
    public static function mensajeBloqueo(?int $empresaId = null): string
    {
        $vigente = self::fechaVigente($empresaId);

        return "No se puede eliminar ni anular este documento: su fecha es anterior o igual al cierre de período vigente ({$vigente->format('d/m/Y')}).";
    }
}
