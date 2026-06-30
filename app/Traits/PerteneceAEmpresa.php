<?php
namespace App\Traits;

use App\Scopes\EmpresaScope;

trait PerteneceAEmpresa
{
    public static function bootPerteneceAEmpresa(): void
    {
        static::addGlobalScope(new EmpresaScope());

        // Al crear, asignar empresa_id automáticamente del usuario autenticado
        static::creating(function ($model) {
            if (empty($model->empresa_id) && auth()->check() && auth()->user()->empresa_id !== null) {
                $model->empresa_id = auth()->user()->empresa_id;
            }
        });
    }
}
