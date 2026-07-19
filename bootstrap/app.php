<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role'       => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'modulo'     => \App\Http\Middleware\VerificarModulo::class,
            'licencia'   => \App\Http\Middleware\VerificarLicenciaVigente::class,
            'no-superadmin' => \App\Http\Middleware\DenegarSuperAdminOperativo::class,
            'solo-superadmin' => \App\Http\Middleware\SoloSuperAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // El mensaje por defecto de Spatie ("User does not have the right
        // permissions") no le dice al usuario qué hacer. Se reemplaza por un
        // mensaje accionable, igual para cualquier permiso que falte.
        $exceptions->render(function (\Spatie\Permission\Exceptions\UnauthorizedException $e, $request) {
            return response()->view('errors.403-permiso', [], 403);
        });
    })->create();
