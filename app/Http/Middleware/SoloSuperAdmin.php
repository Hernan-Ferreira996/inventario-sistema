<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SoloSuperAdmin
{
    /**
     * Gestionar el listado de Empresas del sistema (RUC, plan, módulos
     * contratados de cada tenant) es una operación de plataforma, no de
     * negocio: debe quedar reservada al super-admin real (empresa_id null),
     * no a cualquier usuario con el rol "admin" de su propia empresa —
     * ese rol también lo tienen los admins locales de cada tenant.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user()?->esSuperAdmin()) {
            abort(403, 'Esta sección es exclusiva del administrador de la plataforma.');
        }

        return $next($request);
    }
}
