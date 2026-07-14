<?php
namespace App\Traits;

use App\Scopes\SucursalScope;

trait RestringidoPorSucursal
{
    public static function bootRestringidoPorSucursal(): void
    {
        static::addGlobalScope(new SucursalScope());

        static::creating(function ($model) {
            if (empty($model->sucursal_id) && auth()->check()) {
                $user = auth()->user();
                $sucursalIds = $user->sucursales()->pluck('sucursales.id');
                if ($sucursalIds->count() === 1) {
                    // Autoasignar si el usuario solo tiene una sucursal permitida
                    $model->sucursal_id = $sucursalIds->first();
                } elseif ($sucursalIds->isEmpty() && $user->empresa_id) {
                    // Sin restricción explícita: si la empresa tiene una única sucursal,
                    // no hay ambigüedad posible, se asigna directamente.
                    $sucursalesEmpresa = \App\Models\Sucursal::where('empresa_id', $user->empresa_id)->pluck('id');
                    if ($sucursalesEmpresa->count() === 1) {
                        $model->sucursal_id = $sucursalesEmpresa->first();
                    }
                }
                // Con más de una sucursal posible y sin restricción específica, no se
                // autoasigna: el formulario/controlador debería pedirlo explícitamente
                // (pendiente de UI de selección de sucursal para empresas multi-sucursal).
            }
        });
    }
}
