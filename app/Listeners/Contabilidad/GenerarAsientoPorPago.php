<?php

namespace App\Listeners\Contabilidad;

use App\Events\PagoRegistrado;
use App\Models\AsientoContable;
use App\Support\ModulosActivos;

class GenerarAsientoPorPago
{
    public function handle(PagoRegistrado $event): void
    {
        if (!ModulosActivos::tiene('contabilidad')) {
            return;
        }

        $pago = $event->pago;
        $pago->loadMissing('metodoPago', 'factura');

        $esEfectivo = $pago->metodoPago && str_contains(mb_strtolower($pago->metodoPago->nombre), 'efectivo');
        $cuentaOrigen = $esEfectivo ? '1.1.01' : '1.1.02';

        AsientoContable::crear(
            'Pago factura ' . ($pago->factura->numero_documento ?? $pago->id),
            'pago',
            [
                ['cuenta_codigo' => $cuentaOrigen, 'debe' => $pago->monto, 'haber' => 0, 'descripcion' => 'Cobro de factura'],
                ['cuenta_codigo' => '1.1.03', 'debe' => 0, 'haber' => $pago->monto, 'descripcion' => 'Cancelación cuenta por cobrar'],
            ],
            $pago
        );
    }
}
