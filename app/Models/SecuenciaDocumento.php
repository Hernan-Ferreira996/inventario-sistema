<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class SecuenciaDocumento extends Model
{
    protected $table = 'secuencias_documento';

    protected $fillable = [
        'empresa_id', 'sucursal_id', 'tipo_documento', 'prefijo', 'longitud',
        'proximo_numero', 'reinicio', 'ultimo_anio_reinicio',
    ];

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    public function sucursal(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class);
    }

    /**
     * Reserva y devuelve el siguiente número formateado (prefijo + ceros a la izquierda)
     * para un tipo de documento, de forma atómica (lockForUpdate) para evitar
     * números duplicados ante creaciones concurrentes.
     */
    public static function siguienteNumero(int $empresaId, int $sucursalId, string $tipoDocumento, string $prefijoDefault = ''): string
    {
        return DB::transaction(function () use ($empresaId, $sucursalId, $tipoDocumento, $prefijoDefault) {
            $secuencia = static::where('empresa_id', $empresaId)
                ->where('sucursal_id', $sucursalId)
                ->where('tipo_documento', $tipoDocumento)
                ->lockForUpdate()
                ->first();

            if (!$secuencia) {
                $secuencia = static::create([
                    'empresa_id' => $empresaId,
                    'sucursal_id' => $sucursalId,
                    'tipo_documento' => $tipoDocumento,
                    'prefijo' => $prefijoDefault,
                    'longitud' => 6,
                    'proximo_numero' => 1,
                    'reinicio' => 'nunca',
                ]);
            }

            $anioActual = (int) now()->year;
            if ($secuencia->reinicio === 'anual' && $secuencia->ultimo_anio_reinicio !== $anioActual) {
                $secuencia->proximo_numero = 1;
                $secuencia->ultimo_anio_reinicio = $anioActual;
            }

            $numero = $secuencia->proximo_numero;
            $secuencia->proximo_numero = $numero + 1;
            $secuencia->ultimo_anio_reinicio ??= $anioActual;
            $secuencia->save();

            return $secuencia->prefijo . str_pad((string) $numero, $secuencia->longitud, '0', STR_PAD_LEFT);
        });
    }

    /**
     * Vista previa del próximo número SIN reservarlo (no incrementa), para mostrar
     * en pantallas de "crear" antes de guardar. No usar su resultado como valor final.
     */
    public static function previsualizar(int $empresaId, int $sucursalId, string $tipoDocumento, string $prefijoDefault = ''): string
    {
        $secuencia = static::firstOrCreate(
            ['empresa_id' => $empresaId, 'sucursal_id' => $sucursalId, 'tipo_documento' => $tipoDocumento],
            ['prefijo' => $prefijoDefault, 'longitud' => 6, 'proximo_numero' => 1, 'reinicio' => 'nunca']
        );

        $numero = ($secuencia->reinicio === 'anual' && $secuencia->ultimo_anio_reinicio !== (int) now()->year)
            ? 1
            : $secuencia->proximo_numero;

        return $secuencia->prefijo . str_pad((string) $numero, $secuencia->longitud, '0', STR_PAD_LEFT);
    }
}
