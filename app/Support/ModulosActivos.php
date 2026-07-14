<?php

namespace App\Support;

use App\Models\Modulo;
use Illuminate\Support\Facades\Auth;

class ModulosActivos
{
    public static function paraUsuarioActual(): array
    {
        if (!Auth::check()) {
            return [];
        }
        $user = Auth::user();

        // Super-admin (empresa_id null) ve todos los módulos del catálogo
        if ($user->empresa_id === null) {
            return Modulo::where('activo', true)->pluck('codigo')->all();
        }

        if (!$user->empresa) {
            return [];
        }

        return Modulo::where('activo', true)->get()
            ->filter(fn ($m) => $user->empresa->tieneModulo($m->codigo))
            ->pluck('codigo')->values()->all();
    }

    public static function tiene(string $codigo): bool
    {
        return in_array($codigo, self::paraUsuarioActual(), true);
    }
}
