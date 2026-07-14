<?php

namespace Database\Seeders;

use App\Models\Empresa;
use App\Models\Modulo;
use App\Models\Plan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModulosPlanesSeeder extends Seeder
{
    public function run(): void
    {
        $modulos = [
            ['codigo' => 'inventario',    'nombre' => 'Inventario',           'nucleo' => false, 'orden' => 1, 'icono' => 'bi-box-seam'],
            ['codigo' => 'ventas',        'nombre' => 'Ventas y Facturación', 'nucleo' => false, 'orden' => 2, 'icono' => 'bi-receipt'],
            ['codigo' => 'compras',       'nombre' => 'Compras',              'nucleo' => false, 'orden' => 3, 'icono' => 'bi-bag-check'],
            ['codigo' => 'reportes',      'nombre' => 'Reportes',             'nucleo' => false, 'orden' => 4, 'icono' => 'bi-bar-chart-line'],
            ['codigo' => 'auditoria',     'nombre' => 'Auditoría',            'nucleo' => false, 'orden' => 5, 'icono' => 'bi-clock-history'],
            ['codigo' => 'contabilidad',  'nombre' => 'Contabilidad',         'nucleo' => false, 'orden' => 6, 'icono' => 'bi-journal-text'],
            ['codigo' => 'configuracion', 'nombre' => 'Configuración',        'nucleo' => true,  'orden' => 99, 'icono' => 'bi-gear'],
        ];
        foreach ($modulos as $m) {
            Modulo::firstOrCreate(['codigo' => $m['codigo']], $m);
        }

        $planes = [
            'basico'      => ['nombre' => 'Básico',      'orden' => 1, 'modulos' => ['inventario', 'ventas', 'configuracion']],
            'profesional' => ['nombre' => 'Profesional', 'orden' => 2, 'modulos' => ['inventario', 'ventas', 'compras', 'reportes', 'configuracion']],
            'enterprise'  => ['nombre' => 'Enterprise',  'orden' => 3, 'modulos' => ['inventario', 'ventas', 'compras', 'reportes', 'auditoria', 'contabilidad', 'configuracion']],
        ];
        foreach ($planes as $codigo => $datos) {
            $plan = Plan::firstOrCreate(['codigo' => $codigo], ['nombre' => $datos['nombre'], 'orden' => $datos['orden'], 'activo' => true]);
            $moduloIds = Modulo::whereIn('codigo', $datos['modulos'])->pluck('id');
            $plan->modulos()->syncWithoutDetaching($moduloIds);
        }

        // Retrocompatibilidad: TODA empresa existente sin plan pasa a Enterprise con todos los módulos
        $planEnterprise = Plan::where('codigo', 'enterprise')->first();
        Empresa::whereNull('plan_id')->get()->each(function ($empresa) use ($planEnterprise) {
            $empresa->update(['plan_id' => $planEnterprise->id]);
        });

        // Backfill de sucursal_id histórico: ninguna transacción existente lo tenía
        // seteado (nunca hubo código que lo asignara antes de esta fase).
        // Para empresas con una sola sucursal, todo documento le pertenece por definición.
        foreach (Empresa::has('sucursales', '=', 1)->with('sucursales')->get() as $empresa) {
            $sucursalId = $empresa->sucursales->first()->id;
            foreach (['pedidos_venta', 'presupuestos', 'pedidos_compra', 'facturas', 'notas_credito', 'notas_remision'] as $tabla) {
                DB::table($tabla)->where('empresa_id', $empresa->id)->whereNull('sucursal_id')
                    ->update(['sucursal_id' => $sucursalId]);
            }
        }

        // Derivados (UPDATE...JOIN crudo porque Eloquent no soporta update-join nativo)
        DB::statement("
            UPDATE movimientos_stock m
            JOIN ubicaciones u ON u.id = m.ubicacion_id
            SET m.sucursal_id = u.sucursal_id
            WHERE m.sucursal_id IS NULL
        ");

        DB::statement("
            UPDATE envios e
            JOIN pedidos_venta p ON p.id = e.pedido_id
            SET e.sucursal_id = p.sucursal_id, e.empresa_id = p.empresa_id
            WHERE e.sucursal_id IS NULL
        ");

        DB::statement("
            UPDATE pagos pg
            LEFT JOIN facturas f ON f.id = pg.factura_id
            LEFT JOIN pedidos_venta pv ON pv.id = pg.pedido_id
            SET pg.sucursal_id = COALESCE(f.sucursal_id, pv.sucursal_id)
            WHERE pg.sucursal_id IS NULL
        ");

        DB::statement("
            UPDATE traslados_stock t
            JOIN ubicaciones u ON u.id = t.ubicacion_origen_id
            SET t.sucursal_id = u.sucursal_id
            WHERE t.sucursal_id IS NULL
        ");

        $this->command->info('Módulos, planes y backfill de sucursal_id completados.');
    }
}
