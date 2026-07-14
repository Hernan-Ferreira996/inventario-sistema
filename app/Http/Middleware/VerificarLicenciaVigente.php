<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerificarLicenciaVigente
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return $next($request);
        }

        $user = auth()->user();

        // Super-admin nunca se bloquea, y necesita seguir gestionando empresas
        if ($user->empresa_id === null) {
            return $next($request);
        }

        $empresa = $user->empresa;
        if ($empresa && $empresa->fecha_vencimiento_licencia !== null && $empresa->fecha_vencimiento_licencia->isPast()) {
            abort(403, 'Su licencia venció. Contacte a soporte para renovarla.');
        }

        return $next($request);
    }
}
