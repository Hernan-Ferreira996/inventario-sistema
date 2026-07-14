<?php

namespace App\Listeners\Contabilidad;

use App\Events\FacturaEmitida;
use App\Models\AsientoContable;
use App\Support\ModulosActivos;

class GenerarAsientoPorFactura
{
    public function handle(FacturaEmitida $event): void
    {
        if (!ModulosActivos::tiene('contabilidad')) {
            return;
        }

        $factura = $event->factura;

        $lineas = [
            ['cuenta_codigo' => '1.1.03', 'debe' => $factura->total, 'haber' => 0, 'descripcion' => 'Venta según factura'],
            ['cuenta_codigo' => '4.1', 'debe' => 0, 'haber' => $factura->subtotal, 'descripcion' => 'Venta de mercaderías'],
        ];
        if ($factura->impuesto_total > 0) {
            $lineas[] = ['cuenta_codigo' => '2.1.02', 'debe' => 0, 'haber' => $factura->impuesto_total, 'descripcion' => 'IVA débito fiscal'];
        }

        AsientoContable::crear("Factura {$factura->numero_documento}", 'factura', $lineas, $factura);
    }
}
