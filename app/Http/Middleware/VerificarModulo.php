<?php
namespace App\Http\Middleware;

use App\Support\ModulosActivos;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerificarModulo
{
    public function handle(Request $request, Closure $next, string ...$codigosModulo): Response
    {
        if (!auth()->check()) {
            abort(403);
        }

        // Super-admin siempre pasa
        if (auth()->user()->empresa_id === null) {
            return $next($request);
        }

        foreach ($codigosModulo as $codigo) {
            if (!ModulosActivos::tiene($codigo)) {
                abort(403, "El módulo '{$codigo}' no está habilitado para su empresa. Contacte a soporte para contratarlo.");
            }
        }

        return $next($request);
    }
}
