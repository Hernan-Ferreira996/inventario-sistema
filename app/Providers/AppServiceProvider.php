<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use App\Models\Producto;
use App\Support\Configuracion;
use App\Support\ModulosActivos;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // El sistema usa Bootstrap 5 (CDN), no Tailwind: sin esto, los iconos SVG
        // de la paginación por defecto de Laravel se renderizan sin las clases que
        // los achican y aparecen gigantes (ver bug encontrado en /auditoria).
        Paginator::useBootstrapFive();

        Blade::if('modulo', fn (string $codigo) => ModulosActivos::tiene($codigo));

        // Los listeners de app/Listeners/Contabilidad se registran solos por
        // auto-discovery de Laravel (siguen la convención handle(Evento $e)).
        // No registrarlos también acá a mano: eso los duplicaba y generaba
        // dos asientos contables por cada factura/pago/nota/compra.

        View::composer('layouts.app', function ($view) {
            if (!auth()->check()) {
                return;
            }

            $alertasStockBajo = collect();
            if (auth()->user()->can('productos.ver')) {
                $minimo = (int) (Configuracion::obtener()['sistema_stock_minimo'] ?? 5);
                $alertasStockBajo = Producto::activos()
                    ->withSum('movimientos', 'cantidad')
                    ->having('movimientos_sum_cantidad', '<=', $minimo)
                    ->orHavingNull('movimientos_sum_cantidad')
                    ->limit(10)
                    ->get();
            }

            $view->with('alertasStockBajo', $alertasStockBajo);
            $view->with('modulosActivos', ModulosActivos::paraUsuarioActual());
        });
    }
}
