<?php

namespace App\Listeners\Contabilidad;

use App\Events\FacturaProveedorRegistrada;
use App\Models\AsientoContable;
use App\Support\ModulosActivos;

class GenerarAsientoPorFacturaProveedor
{
    public function handle(FacturaProveedorRegistrada $event): void
    {
        if (!ModulosActivos::tiene('contabilidad')) {
            return;
        }

        $factura = $event->facturaProveedor;

        $lineas = [
            ['cuenta_codigo' => '5.2', 'debe' => $factura->subtotal, 'haber' => 0, 'descripcion' => 'Gasto según factura de proveedor'],
        ];
        if ($factura->iva_total > 0) {
            $lineas[] = ['cuenta_codigo' => '1.1.05', 'debe' => $factura->iva_total, 'haber' => 0, 'descripcion' => 'IVA crédito fiscal'];
        }

        $montoAPagar = $factura->total - $factura->retencion_monto;
        $lineas[] = ['cuenta_codigo' => '2.1.01', 'debe' => 0, 'haber' => $montoAPagar, 'descripcion' => 'Cuenta por pagar al proveedor'];

        if ($factura->retencion_monto > 0) {
            $lineas[] = ['cuenta_codigo' => '2.1.03', 'debe' => 0, 'haber' => $factura->retencion_monto, 'descripcion' => 'Retención de IVA a pagar'];
        }

        AsientoContable::crear("Factura de Proveedor {$factura->numero_referencia}", 'factura_proveedor', $lineas, $factura);
    }
}
