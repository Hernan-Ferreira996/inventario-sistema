<?php

namespace Database\Seeders;

use App\Models\CatalogoValor;
use Illuminate\Database\Seeder;

class CatalogoValoresSeeder extends Seeder
{
    public function run(): void
    {
        $grupos = [
            'pedidos_venta.estado' => [
                ['activo', 'Activo', '#22c55e', '#ffffff'],
                ['completado', 'Completado', '#34d399', '#000000'],
                ['cancelado', 'Cancelado', '#f87171', '#ffffff'],
            ],
            'pedidos_venta.estado_factura' => [
                ['pendiente', 'Pendiente', '#fbbf24', '#000000'],
                ['parcial', 'Parcial', '#fb923c', '#ffffff'],
                ['completado', 'Facturado', '#a78bfa', '#ffffff'],
            ],
            'facturas.estado' => [
                ['pendiente', 'Pendiente', '#fbbf24', '#000000'],
                ['parcial', 'Parcial', '#fb923c', '#ffffff'],
                ['pagada', 'Pagada', '#34d399', '#000000'],
                ['anulada', 'Anulada', '#f87171', '#ffffff'],
            ],
            'facturas_proveedor.estado' => [
                ['pendiente', 'Pendiente', '#fbbf24', '#000000'],
                ['parcial', 'Parcial', '#fb923c', '#ffffff'],
                ['pagada', 'Pagada', '#34d399', '#000000'],
                ['vencida', 'Vencida', '#f87171', '#ffffff'],
                ['anulada', 'Anulada', '#94a3b8', '#ffffff'],
            ],
            'envios.estado' => [
                ['preparando', 'Preparando', '#fbbf24', '#000000'],
                ['enviado', 'Enviado', '#2dd4bf', '#000000'],
                ['entregado', 'Entregado', '#34d399', '#000000'],
                ['devuelto', 'Devuelto', '#f87171', '#ffffff'],
            ],
            'pedidos_compra.estado' => [
                ['pendiente', 'Pendiente', '#fbbf24', '#000000'],
                ['parcial', 'Parcial', '#fb923c', '#ffffff'],
                ['completado', 'Completado', '#34d399', '#000000'],
                ['cancelado', 'Cancelado', '#f87171', '#ffffff'],
            ],
            'pedidos_compra.tipo' => [
                ['local', 'Local', '#60a5fa', '#ffffff'],
                ['importada', 'Importada', '#a78bfa', '#ffffff'],
            ],
            'notas_credito.motivo' => [
                ['devolucion_total', 'Devolución Total', '#f87171', '#ffffff'],
                ['devolucion_parcial', 'Devolución Parcial', '#fb923c', '#ffffff'],
                ['descuento', 'Descuento', '#60a5fa', '#ffffff'],
                ['anulacion', 'Anulación', '#94a3b8', '#ffffff'],
                ['otro', 'Otro', '#94a3b8', '#ffffff'],
            ],
            'notas_remision.motivo' => [
                ['venta', 'Venta', '#22c55e', '#ffffff'],
                ['consignacion', 'Consignación', '#60a5fa', '#ffffff'],
                ['traslado', 'Traslado', '#a78bfa', '#ffffff'],
                ['devolucion', 'Devolución', '#fb923c', '#ffffff'],
                ['otro', 'Otro', '#94a3b8', '#ffffff'],
            ],
            'presupuestos.estado' => [
                ['pendiente', 'Pendiente', '#fbbf24', '#000000'],
                ['aprobado', 'Aprobado', '#34d399', '#000000'],
                ['rechazado', 'Rechazado', '#f87171', '#ffffff'],
                ['vencido', 'Vencido', '#94a3b8', '#ffffff'],
                ['convertido', 'Convertido', '#a78bfa', '#ffffff'],
            ],
            'clientes.tipo_precio' => [
                ['minorista', 'Minorista', '#60a5fa', '#ffffff'],
                ['mayorista', 'Mayorista', '#a78bfa', '#ffffff'],
            ],
            'interacciones.tipo' => [
                ['llamada', 'Llamada', '#60a5fa', '#ffffff'],
                ['reunion', 'Reunión', '#a78bfa', '#ffffff'],
                ['email', 'Email', '#2dd4bf', '#000000'],
                ['nota', 'Nota', '#94a3b8', '#ffffff'],
            ],
            'cuentas_contables.tipo' => [
                ['activo', 'Activo', '#60a5fa', '#ffffff'],
                ['pasivo', 'Pasivo', '#f87171', '#ffffff'],
                ['patrimonio', 'Patrimonio', '#a78bfa', '#ffffff'],
                ['ingreso', 'Ingreso', '#22c55e', '#ffffff'],
                ['gasto', 'Gasto', '#fb923c', '#ffffff'],
            ],
            'presupuestos.etapa' => [
                ['prospecto', 'Prospecto', '#94a3b8', '#ffffff'],
                ['cotizacion', 'Cotización', '#60a5fa', '#ffffff'],
                ['negociacion', 'Negociación', '#fb923c', '#ffffff'],
                ['ganado', 'Ganado', '#22c55e', '#ffffff'],
                ['perdido', 'Perdido', '#f87171', '#ffffff'],
            ],
        ];

        foreach ($grupos as $grupo => $valores) {
            foreach ($valores as $i => [$codigo, $etiqueta, $color, $colorTexto]) {
                CatalogoValor::firstOrCreate(
                    ['empresa_id' => null, 'grupo' => $grupo, 'codigo' => $codigo],
                    ['etiqueta' => $etiqueta, 'color' => $color, 'color_texto' => $colorTexto, 'orden' => $i, 'activo' => true, 'protegido' => true]
                );
            }
        }

        $this->command->info('Catálogo de valores (estados/motivos) inicializado.');
    }
}
