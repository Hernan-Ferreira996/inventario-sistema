<?php

namespace App\Listeners\Contabilidad;

use App\Events\CompraRecibida;
use App\Models\AsientoContable;
use App\Support\ModulosActivos;

class GenerarAsientoPorCompra
{
    public function handle(CompraRecibida $event): void
    {
        if (!ModulosActivos::tiene('contabilidad')) {
            return;
        }

        $recepcion = $event->recepcion;
        $recepcion->loadMissing(['detalles', 'pedidoCompra.detalles']);

        $preciosPorProducto = $recepcion->pedidoCompra->detalles->keyBy('producto_id');

        $total = 0.0;
        foreach ($recepcion->detalles as $d) {
            $precio = (float) ($preciosPorProducto->get($d->producto_id)->precio_unitario ?? 0);
            $total += (float) $d->cantidad * $precio;
        }

        if ($total <= 0) {
            return; // nada que contabilizar (recepción sin precio de referencia)
        }

        AsientoContable::crear(
            "Recepción de compra {$recepcion->numero_referencia} - Pedido {$recepcion->pedidoCompra->numero_referencia}",
            'compra',
            [
                ['cuenta_codigo' => '1.1.04', 'debe' => $total, 'haber' => 0, 'descripcion' => 'Ingreso de mercaderías'],
                ['cuenta_codigo' => '2.1.01', 'debe' => 0, 'haber' => $total, 'descripcion' => 'Cuenta por pagar al proveedor'],
            ],
            $recepcion
        );
    }
}
