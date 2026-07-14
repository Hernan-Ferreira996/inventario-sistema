<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DenegarSuperAdminOperativo
{
    /**
     * El super-admin (empresa_id null) administra la plataforma y no debe crear
     * documentos operativos de una empresa: eso los dejaría con empresa_id NULL,
     * invisibles para los usuarios de la empresa correspondiente (ver EmpresaScope).
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()?->esSuperAdmin()) {
            abort(403, 'El super-admin administra la plataforma y no puede crear documentos operativos de una empresa. Inicie sesión con un usuario de la empresa correspondiente.');
        }

        return $next($request);
    }
}
