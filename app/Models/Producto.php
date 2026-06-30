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
        'precio_venta_minorista', 'precio_venta_mayorista', 'activo',
    ];

    protected $casts = [
        'precio_compra' => 'decimal:2',
        'precio_venta_minorista' => 'decimal:2',
        'precio_venta_mayorista' => 'decimal:2',
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

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }
}
