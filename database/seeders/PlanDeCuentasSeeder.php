<?php

namespace Database\Seeders;

use App\Models\CuentaContable;
use App\Models\Empresa;
use Illuminate\Database\Seeder;

class PlanDeCuentasSeeder extends Seeder
{
    /**
     * Plan de cuentas básico por empresa. Los códigos de las cuentas imputables
     * son referenciados directamente por los listeners de asientos automáticos
     * (app/Listeners/Contabilidad/*), no cambiarlos sin actualizar ambos lados.
     */
    public function run(): void
    {
        $cuentas = [
            // codigo, nombre, tipo, naturaleza, imputable, padre_codigo
            ['1',      'ACTIVO',                  'activo',     'deudora',   false, null],
            ['1.1',    'Activo Corriente',        'activo',     'deudora',   false, '1'],
            ['1.1.01', 'Caja',                    'activo',     'deudora',   true,  '1.1'],
            ['1.1.02', 'Bancos',                  'activo',     'deudora',   true,  '1.1'],
            ['1.1.03', 'Cuentas por Cobrar Clientes', 'activo', 'deudora',   true,  '1.1'],
            ['1.1.04', 'Mercaderías',             'activo',     'deudora',   true,  '1.1'],
            ['1.1.05', 'IVA Crédito Fiscal',      'activo',     'deudora',   true,  '1.1'],

            ['2',      'PASIVO',                  'pasivo',     'acreedora', false, null],
            ['2.1',    'Pasivo Corriente',        'pasivo',     'acreedora', false, '2'],
            ['2.1.01', 'Cuentas por Pagar Proveedores', 'pasivo', 'acreedora', true, '2.1'],
            ['2.1.02', 'IVA Débito Fiscal',       'pasivo',     'acreedora', true,  '2.1'],
            ['2.1.03', 'Retenciones IVA a Pagar', 'pasivo',     'acreedora', true,  '2.1'],

            ['3',      'PATRIMONIO',              'patrimonio', 'acreedora', false, null],
            ['3.1',    'Capital Social',          'patrimonio', 'acreedora', true,  '3'],
            ['3.2',    'Resultados Acumulados',   'patrimonio', 'acreedora', true,  '3'],

            ['4',      'INGRESOS',                'ingreso',    'acreedora', false, null],
            ['4.1',    'Ventas',                  'ingreso',    'acreedora', true,  '4'],
            ['4.2',    'Devoluciones sobre Ventas', 'ingreso',  'deudora',   true,  '4'],

            ['5',      'GASTOS',                  'gasto',      'deudora',   false, null],
            ['5.1',    'Costo de Mercaderías Vendidas', 'gasto', 'deudora',  true,  '5'],
            ['5.2',    'Gastos Operativos',       'gasto',      'deudora',   true,  '5'],
        ];

        foreach (Empresa::all() as $empresa) {
            $idsPorCodigo = [];
            foreach ($cuentas as [$codigo, $nombre, $tipo, $naturaleza, $imputable, $padreCodigo]) {
                $cuenta = CuentaContable::firstOrCreate(
                    ['empresa_id' => $empresa->id, 'codigo' => $codigo],
                    [
                        'nombre' => $nombre,
                        'tipo' => $tipo,
                        'naturaleza' => $naturaleza,
                        'imputable' => $imputable,
                        'activo' => true,
                        'cuenta_padre_id' => $padreCodigo ? ($idsPorCodigo[$padreCodigo] ?? null) : null,
                    ]
                );
                $idsPorCodigo[$codigo] = $cuenta->id;
            }
        }

        $this->command->info('Plan de cuentas estándar inicializado por empresa.');
    }
}
