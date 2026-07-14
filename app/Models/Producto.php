<?php

namespace App\Models;

use App\Traits\PerteneceAEmpresa;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Producto extends Model
{
    use PerteneceAEmpresa;
    use SoftDeletes, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['codigo', 'nombre', 'precio_compra', 'precio_venta_minorista', 'precio_venta_mayorista', 'activo'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('productos');
    }

    protected $table = 'productos';

    protected $fillable = [
        'codigo', 'nombre', 'descripcion', 'categoria_id', 'unidad_id',
        'impuesto_id', 'imagen', 'precio_compra',
        'precio_venta_minorista', 'precio_venta_mayorista', 'stock_minimo', 'activo',
    ];

    protected $casts = [
        'precio_compra' => 'decimal:2',
        'precio_venta_minorista' => 'decimal:2',
        'precio_venta_mayorista' => 'decimal:2',
        'stock_minimo' => 'decimal:2',
        'activo' => 'boolean',
    ];

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    public function unidad(): BelongsTo
    {
        return $this->belongsTo(Unidad::class);
    }

    public function impuesto(): BelongsTo
    {
        return $this->belongsTo(Impuesto::class);
    }

    public function movimientos(): HasMany
    {
        return $this->hasMany(MovimientoStock::class);
    }

    public function stockTotal(): float
    {
        return (float) $this->movimientos()->sum('cantidad');
    }

    public function stockEnUbicacion(int $ubicacionId): float
    {
        return (float) $this->movimientos()->where('ubicacion_id', $ubicacionId)->sum('cantidad');
    }

    /**
     * Cantidad reservada en pedidos de venta activos aún no despachados por
     * completo (la mercadería sale físicamente recién con la Nota de
     * Remisión, ver NotaRemisionController). $ubicacionId filtra por la
     * ubicación de despacho asignada al pedido.
     */
    public function stockComprometido(?int $ubicacionId = null): float
    {
        $query = DetallePedidoVenta::query()
            ->where('producto_id', $this->id)
            ->whereColumn('cantidad', '>', 'cantidad_enviada')
            ->whereHas('pedido', function ($q) use ($ubicacionId) {
                $q->where('estado', 'activo');
                if ($ubicacionId) {
                    $q->where('ubicacion_id', $ubicacionId);
                }
            });

        return (float) $query->sum(\Illuminate\Support\Facades\DB::raw('cantidad - cantidad_enviada'));
    }

    public function stockDisponible(?int $ubicacionId = null): float
    {
        $total = $ubicacionId !== null ? $this->stockEnUbicacion($ubicacionId) : $this->stockTotal();

        return $total - $this->stockComprometido($ubicacionId);
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Umbral de stock mínimo a usar para este producto: el propio si fue
     * definido, o si no el valor por defecto configurado en la empresa.
     */
    public function stockMinimoEfectivo(): float
    {
        if ($this->stock_minimo !== null) {
            return (float) $this->stock_minimo;
        }

        return (float) (\App\Support\Configuracion::obtener()['sistema_stock_minimo'] ?? 5);
    }

    /**
     * Filtra productos cuyo stock total está en o por debajo de su umbral
     * mínimo efectivo. Usa una subconsulta correlacionada propia en WHERE
     * (en vez de comparar contra el alias de withSum en HAVING) porque
     * MariaDB con ONLY_FULL_GROUP_BY rechaza referenciar una columna no
     * agregada como stock_minimo en HAVING sin un GROUP BY.
     */
    public function scopeStockBajo($query)
    {
        $minimoDefault = (float) (\App\Support\Configuracion::obtener()['sistema_stock_minimo'] ?? 5);

        return $query->whereRaw(
            '(select coalesce(sum(ms.cantidad), 0) from movimientos_stock ms where ms.producto_id = productos.id) <= COALESCE(productos.stock_minimo, ?)',
            [$minimoDefault]
        );
    }
}
