<?php

namespace App\Models;

use App\Traits\PerteneceAEmpresa;
use App\Traits\RestringidoPorSucursal;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class MovimientoStock extends Model
{
    use PerteneceAEmpresa;
    use RestringidoPorSucursal;
    protected $table = 'movimientos_stock';

    protected $fillable = [
        'producto_id', 'ubicacion_id', 'usuario_id',
        'cantidad', 'tipo', 'referencia', 'notas', 'fecha_movimiento',
        'origen_documento_type', 'origen_documento_id',
    ];

    protected $casts = [
        'cantidad' => 'decimal:2',
        'fecha_movimiento' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $model) {
            if (empty($model->sucursal_id) && $model->ubicacion_id) {
                $model->sucursal_id = Ubicacion::find($model->ubicacion_id)?->sucursal_id;
            }
        });
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    public function ubicacion(): BelongsTo
    {
        return $this->belongsTo(Ubicacion::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function origenDocumento(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Genera movimientos inversos para todos los movimientos de stock
     * vinculados a $origenDocumento (por su origen_documento polimórfico).
     * No borra los movimientos originales: el ledger es append-only, así
     * que anular algo se registra como un movimiento nuevo, nunca como la
     * desaparición de uno viejo.
     */
    public static function revertir($origenDocumento, string $motivo): void
    {
        $movimientos = static::where('origen_documento_type', $origenDocumento->getMorphClass())
            ->where('origen_documento_id', $origenDocumento->id)
            ->get();

        foreach ($movimientos as $m) {
            static::create([
                'producto_id'      => $m->producto_id,
                'ubicacion_id'     => $m->ubicacion_id,
                'usuario_id'       => auth()->id() ?? $m->usuario_id,
                'cantidad'         => -$m->cantidad,
                'tipo'             => 'ajuste',
                'referencia'       => $motivo,
                'notas'            => "Reversa automática del movimiento #{$m->id}",
                'fecha_movimiento' => now(),
            ]);
        }
    }
}
