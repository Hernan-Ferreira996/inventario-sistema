<?php

namespace App\Models;

use App\Support\Numeracion;
use App\Traits\PerteneceAEmpresa;
use App\Traits\RestringidoPorSucursal;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AsientoContable extends Model
{
    use PerteneceAEmpresa;
    use RestringidoPorSucursal;

    protected $table = 'asientos_contables';

    protected $fillable = ['usuario_id', 'numero', 'fecha', 'concepto', 'origen'];

    protected $casts = ['fecha' => 'date'];

    public function movimientos(): HasMany
    {
        return $this->hasMany(MovimientoContable::class, 'asiento_id');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function origenDocumento(): MorphTo
    {
        return $this->morphTo();
    }

    public function getTotalDebeAttribute(): float
    {
        return (float) $this->movimientos->sum('debe');
    }

    public function getTotalHaberAttribute(): float
    {
        return (float) $this->movimientos->sum('haber');
    }

    public function getBalanceadoAttribute(): bool
    {
        return round($this->total_debe, 2) === round($this->total_haber, 2);
    }

    /**
     * Crea un asiento balanceado con sus movimientos en una sola transacción.
     * $lineas = [['cuenta_codigo' => '1.1.01', 'debe' => 100, 'haber' => 0, 'descripcion' => '...'], ...]
     * Lanza RuntimeException si no cuadra debe=haber o si falta alguna cuenta del plan.
     */
    public static function crear(string $concepto, string $origen, array $lineas, $origenDocumento = null): self
    {
        return DB::transaction(function () use ($concepto, $origen, $lineas, $origenDocumento) {
            $empresaId = Auth::user()->empresa_id;

            $asiento = new self([
                'usuario_id' => Auth::id(),
                'numero'     => Numeracion::siguiente('asientos_contables'),
                'fecha'      => now()->toDateString(),
                'concepto'   => $concepto,
                'origen'     => $origen,
            ]);
            if ($origenDocumento) {
                $asiento->origenDocumento()->associate($origenDocumento);
            }
            $asiento->save();

            foreach ($lineas as $l) {
                $cuenta = CuentaContable::where('empresa_id', $empresaId)->where('codigo', $l['cuenta_codigo'])->first();
                if (!$cuenta) {
                    throw new \RuntimeException("Cuenta contable '{$l['cuenta_codigo']}' no existe en el plan de cuentas de la empresa.");
                }
                $asiento->movimientos()->create([
                    'cuenta_contable_id' => $cuenta->id,
                    'debe'        => $l['debe'] ?? 0,
                    'haber'       => $l['haber'] ?? 0,
                    'descripcion' => $l['descripcion'] ?? null,
                ]);
            }

            $asiento->load('movimientos');
            if (!$asiento->balanceado) {
                throw new \RuntimeException("Asiento contable desbalanceado: {$concepto} (debe={$asiento->total_debe}, haber={$asiento->total_haber}).");
            }

            return $asiento;
        });
    }

    /**
     * Busca el asiento generado automáticamente para $origenDocumento (una
     * Factura, Pago, NotaCredito, etc.), si existe.
     */
    public static function buscarPorOrigen($origenDocumento): ?self
    {
        return static::where('origen_documento_type', $origenDocumento->getMorphClass())
            ->where('origen_documento_id', $origenDocumento->id)
            ->first();
    }

    /**
     * Genera el asiento inverso de $original (debe/haber invertidos, mismas
     * cuentas), en vez de borrarlo: los asientos nunca se eliminan, se
     * anulan con una contrapartida — así el libro diario nunca pierde el
     * rastro de lo que pasó.
     */
    public static function revertir(self $original, string $motivo): self
    {
        $original->loadMissing('movimientos.cuenta');

        $lineas = $original->movimientos->map(fn(MovimientoContable $m) => [
            'cuenta_codigo' => $m->cuenta->codigo,
            'debe'          => (float) $m->haber,
            'haber'         => (float) $m->debe,
            'descripcion'   => 'Reversa: ' . ($m->descripcion ?? $original->concepto),
        ])->all();

        return static::crear("Reversa: {$motivo}", 'reversa', $lineas, $original);
    }
}
