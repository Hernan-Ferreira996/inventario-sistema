<?php

namespace App\Support;

use App\Models\SecuenciaDocumento;
use App\Models\Sucursal;
use Illuminate\Support\Facades\Auth;

class Numeracion
{
    /**
     * Genera el siguiente número para un tipo de documento, resolviendo
     * empresa/sucursal del usuario autenticado. $sucursalId explícito
     * tiene prioridad (usado cuando el documento ya trae su propia sucursal).
     */
    public static function siguiente(string $tipoDocumento, ?int $sucursalId = null, string $prefijoDefault = ''): string
    {
        [$empresaId, $sucursalId] = self::resolver($sucursalId);
        return SecuenciaDocumento::siguienteNumero($empresaId, $sucursalId, $tipoDocumento, $prefijoDefault);
    }

    /**
     * Solo para mostrar en pantallas de "crear" antes de guardar: no reserva el número.
     */
    public static function previsualizar(string $tipoDocumento, ?int $sucursalId = null, string $prefijoDefault = ''): string
    {
        [$empresaId, $sucursalId] = self::resolver($sucursalId);
        return SecuenciaDocumento::previsualizar($empresaId, $sucursalId, $tipoDocumento, $prefijoDefault);
    }

    private static function resolver(?int $sucursalId): array
    {
        $user = Auth::user();
        $empresaId = $user?->empresa_id;

        $sucursalId ??= $user?->sucursales()->exists() ? $user->sucursales()->first()->id : null;

        if (!$empresaId) {
            // Super-admin u otro contexto sin empresa: no debería llegar hasta acá
            // gracias a los middlewares de la Fase 1, pero se cubre el caso límite.
            $empresaId = optional(\App\Models\Empresa::first())->id;
        }

        $sucursalId ??= optional(Sucursal::where('empresa_id', $empresaId)->where('principal', true)->first()
            ?? Sucursal::where('empresa_id', $empresaId)->first())->id;

        return [$empresaId, $sucursalId];
    }
}
