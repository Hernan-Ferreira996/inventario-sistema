<?php

namespace Database\Seeders;

use App\Models\SecuenciaDocumento;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SecuenciasDocumentoSeeder extends Seeder
{
    /**
     * Por cada tabla existente: agrupa por empresa/sucursal, calcula el máximo
     * número ya usado (quitando el prefijo) e inicializa la secuencia en max+1,
     * para no colisionar con la numeración histórica generada por count()/max(id).
     */
    public function run(): void
    {
        $tipos = [
            'pedidos_venta'  => ['columna' => 'numero_referencia', 'prefijo' => 'PV-',  'longitud' => 6],
            'presupuestos'   => ['columna' => 'numero_documento',  'prefijo' => 'PRE-', 'longitud' => 6],
            'pedidos_compra' => ['columna' => 'numero_referencia', 'prefijo' => 'PC-',  'longitud' => 6],
            'facturas'       => ['columna' => 'numero_factura',    'prefijo' => '',     'longitud' => 7],
            'notas_credito'  => ['columna' => 'numero_documento',  'prefijo' => '',     'longitud' => 7],
            'notas_remision' => ['columna' => 'numero_documento',  'prefijo' => '',     'longitud' => 7],
            'envios'         => ['columna' => 'numero_envio',      'prefijo' => 'ENV-', 'longitud' => 6],
        ];

        foreach ($tipos as $tabla => $cfg) {
            $filas = DB::table($tabla)
                ->select('empresa_id', 'sucursal_id', $cfg['columna'] . ' as numero')
                ->whereNotNull('empresa_id')
                ->whereNotNull('sucursal_id')
                ->get();

            $maximos = [];
            foreach ($filas as $fila) {
                $numero = $fila->numero;
                if ($cfg['prefijo'] !== '' && str_starts_with($numero, $cfg['prefijo'])) {
                    $numero = substr($numero, strlen($cfg['prefijo']));
                }
                if (!preg_match('/^\d+$/', $numero)) {
                    continue; // valores no numéricos (ej. duplicados renombrados con sufijo -DUP)
                }
                $clave = $fila->empresa_id . ':' . $fila->sucursal_id;
                $maximos[$clave] = max($maximos[$clave] ?? 0, (int) $numero);
            }

            foreach ($maximos as $clave => $max) {
                [$empresaId, $sucursalId] = explode(':', $clave);
                // firstOrCreate (no updateOrCreate): si ya existe, no se pisa una
                // configuración de prefijo/reinicio que un admin haya editado luego.
                SecuenciaDocumento::firstOrCreate(
                    ['empresa_id' => $empresaId, 'sucursal_id' => $sucursalId, 'tipo_documento' => $tabla],
                    ['prefijo' => $cfg['prefijo'], 'longitud' => $cfg['longitud'], 'proximo_numero' => $max + 1, 'reinicio' => 'nunca']
                );
            }
        }

        $this->command->info('Secuencias de numeración inicializadas desde los máximos históricos.');
    }
}
