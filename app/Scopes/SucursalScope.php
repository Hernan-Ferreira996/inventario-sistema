<?php
namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class SucursalScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        if (!auth()->check()) {
            return;
        }

        $user = auth()->user();

        // Super-admin ve todo
        if ($user->empresa_id === null) {
            return;
        }

        $sucursalIds = $user->sucursales()->pluck('sucursales.id');

        // Sin restricciones asignadas = ve todas las sucursales de su empresa (retrocompatible)
        if ($sucursalIds->isEmpty()) {
            return;
        }

        $builder->whereIn($model->getTable() . '.sucursal_id', $sucursalIds);
    }
}
