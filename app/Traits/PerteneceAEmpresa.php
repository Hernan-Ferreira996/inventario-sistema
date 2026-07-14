<?php
namespace App\Traits;

use App\Scopes\EmpresaScope;
use Illuminate\Validation\ValidationException;

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

            // El super-admin (empresa_id null) administra la plataforma, no una empresa
            // puntual: si intenta crear un registro de este tipo sin empresa_id explícito,
            // quedaría huérfano e invisible para la empresa dueña (ver EmpresaScope). Se
            // permite empresa_id null solo fuera de un contexto autenticado (seeders,
            // comandos artisan), donde es el estado esperado para datos base/demo.
            if (empty($model->empresa_id) && auth()->check() && auth()->user()->empresa_id === null) {
                throw ValidationException::withMessages([
                    'empresa_id' => 'El super-admin administra la plataforma y no puede crear este registro sin una empresa asociada. Inicie sesión con un usuario de la empresa correspondiente.',
                ]);
            }
        });
    }
}
