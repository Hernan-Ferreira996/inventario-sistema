<?php

namespace App\Models;

use App\Traits\PerteneceAEmpresa;
use App\Traits\RestringidoPorSucursal;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class PedidoCompra extends Model
{
    use PerteneceAEmpresa;
    use RestringidoPorSucursal;
    use LogsActivity;

    protected $table = 'pedidos_compra';

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['numero_referencia', 'total', 'estado'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('pedidos_compra');
    }

    protected $fillable = [
        'proveedor_id', 'usuario_id', 'ubicacion_id', 'tipo', 'centro_costo_id',
        'numero_referencia', 'comentarios', 'fecha_pedido',
        'fecha_esperada', 'total', 'impuesto_incluido', 'estado',
    ];

    protected $casts = [
        'fecha_pedido' => 'date',
        'fecha_esperada' => 'date',
        'total' => 'decimal:2',
        'impuesto_incluido' => 'boolean',
    ];

    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function ubicacion(): BelongsTo
    {
        return $this->belongsTo(Ubicacion::class);
    }

    public function centroCosto(): BelongsTo
    {
        return $this->belongsTo(CentroCosto::class);
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(DetallePedidoCompra::class, 'pedido_compra_id');
    }

    public function recepciones(): HasMany
    {
        return $this->hasMany(RecepcionCompra::class, 'pedido_compra_id');
    }
}
