<?php

namespace App\Listeners\Contabilidad;

use App\Events\NotaCreditoEmitida;
use App\Models\AsientoContable;
use App\Support\ModulosActivos;

class GenerarAsientoPorNotaCredito
{
    public function handle(NotaCreditoEmitida $event): void
    {
        if (!ModulosActivos::tiene('contabilidad')) {
            return;
        }

        $nota = $event->notaCredito;

        $lineas = [
            ['cuenta_codigo' => '4.2', 'debe' => $nota->subtotal, 'haber' => 0, 'descripcion' => 'Devolución/anulación de venta'],
        ];
        if ($nota->impuesto_total > 0) {
            $lineas[] = ['cuenta_codigo' => '2.1.02', 'debe' => $nota->impuesto_total, 'haber' => 0, 'descripcion' => 'Reverso IVA débito fiscal'];
        }
        $lineas[] = ['cuenta_codigo' => '1.1.03', 'debe' => 0, 'haber' => $nota->total, 'descripcion' => 'Reverso cuenta por cobrar'];

        AsientoContable::crear("Nota de crédito {$nota->numero_completo}", 'nota_credito', $lineas, $nota);
    }
}
