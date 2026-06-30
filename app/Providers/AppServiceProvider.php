<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Producto;
use App\Support\Configuracion;

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
        });
    }
}
