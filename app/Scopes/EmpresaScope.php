<?php
namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class EmpresaScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        if (!auth()->check()) {
            return;
        }

        $empresaId = auth()->user()->empresa_id;

        // Super-admin (empresa_id = null) ve todos los registros
        if ($empresaId !== null) {
            $builder->where($model->getTable() . '.empresa_id', $empresaId);
        }
    }
}
