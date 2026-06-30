<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Empresa;
use App\Models\Sucursal;

class EmpresaSeeder extends Seeder
{
    public function run(): void
    {
        // Leer configuracion actual
        $config = [];
        if (Storage::exists("configuracion.json")) {
            $config = json_decode(Storage::get("configuracion.json"), true) ?? [];
        }

        // Crear la empresa principal desde la configuracion existente
        $empresa = Empresa::firstOrCreate(
            ['ruc' => $config['empresa_ruc'] ?? '5054287'],
            [
                'nombre'                  => $config['empresa_nombre'] ?? 'Mi Empresa',
                'nombre_fantasia'         => $config['empresa_nombre_fantasia'] ?? '',
                'dv'                      => $config['empresa_dv'] ?? '7',
                'telefono'                => $config['empresa_telefono'] ?? '',
                'email'                   => $config['empresa_email'] ?? '',
                'web'                     => $config['empresa_web'] ?? '',
                'direccion'               => $config['empresa_direccion'] ?? '',
                'ciudad'                  => $config['empresa_ciudad'] ?? '',
                'pais'                    => $config['empresa_pais'] ?? 'Paraguay',
                'moneda'                  => $config['empresa_moneda'] ?? 'PYG',
                'simbolo'                 => $config['empresa_simbolo'] ?? 'Gs.',
                'fact_timbrado'           => $config['fact_timbrado'] ?? '',
                'fact_fecha_inicio_vigencia' => !empty($config['fact_fecha_inicio_vigencia'])
                    ? $config['fact_fecha_inicio_vigencia'] : null,
                'fact_establecimiento'    => $config['fact_establecimiento'] ?? '001',
                'fact_punto_expedicion'   => $config['fact_punto_expedicion'] ?? '001',
                'fact_modo'               => $config['fact_modo'] ?? 'local',
                'timezone'                => $config['sistema_timezone'] ?? 'America/Asuncion',
                'decimales'               => (int)($config['sistema_decimales'] ?? 0),
                'stock_minimo'            => (int)($config['sistema_stock_minimo'] ?? 5),
                'activo'                  => true,
            ]
        );

        // Crear sucursal principal
        $sucursal = Sucursal::firstOrCreate(
            ['empresa_id' => $empresa->id, 'codigo' => $empresa->fact_establecimiento],
            [
                'nombre'    => 'Casa Matriz',
                'direccion' => $empresa->direccion,
                'ciudad'    => $empresa->ciudad,
                'telefono'  => $empresa->telefono,
                'principal' => true,
                'activo'    => true,
            ]
        );

        $eid = $empresa->id;
        $sid = $sucursal->id;

        // Asignar empresa_id a todos los registros existentes que no lo tengan

        // Usuarios
        DB::table('users')->whereNull('empresa_id')->update(['empresa_id' => $eid]);
        // Dejar al admin como super-admin (empresa_id null = ve todo)
        DB::table('users')->where('email', 'admin@inventario.com')->update(['empresa_id' => null]);

        // Catálogos
        foreach (['categorias','unidades','impuestos','terminos_pago','metodos_pago',
                  'clientes','proveedores','productos'] as $tabla) {
            DB::table($tabla)->whereNull('empresa_id')->update(['empresa_id' => $eid]);
        }

        // Ubicaciones → Depósitos (asignar empresa + sucursal)
        DB::table('ubicaciones')->whereNull('empresa_id')->update(['empresa_id' => $eid, 'sucursal_id' => $sid]);

        // Documentos transaccionales
        foreach (['pedidos_venta','pedidos_compra','presupuestos','facturas',
                  'notas_credito','notas_remision','movimientos_stock','pagos','traslados_stock'] as $tabla) {
            $update = ['empresa_id' => $eid];
            if (in_array($tabla, ['pedidos_venta','pedidos_compra','presupuestos','facturas','notas_credito','notas_remision'])) {
                $update['sucursal_id'] = $sid;
            }
            DB::table($tabla)->whereNull('empresa_id')->update($update);
        }

        $this->command->info("Empresa '{$empresa->nombre}' creada con ID {$eid}");
        $this->command->info("Sucursal '{$sucursal->nombre}' creada con ID {$sid}");
        $this->command->info("Todos los registros existentes asignados a empresa_id={$eid}");
    }
}
