<?php
namespace App\Models;

use App\Traits\PerteneceAEmpresa;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Presupuesto extends Model
{
    use PerteneceAEmpresa;
    use LogsActivity;

    protected $table = "presupuestos";
    protected $fillable = [
        "cliente_id", "usuario_id", "pedido_id", "numero_documento",
        "fecha_emision", "fecha_validez", "comentarios",
        "subtotal", "impuesto_total", "total", "descuento_global", "monto_descuento", "estado",
    ];
    protected $casts = [
        "fecha_emision" => "date", "fecha_validez" => "date",
        "subtotal" => "decimal:2", "impuesto_total" => "decimal:2", "total" => "decimal:2",
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(["numero_documento", "total", "estado"])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName("presupuestos");
    }

    public function cliente(): BelongsTo { return $this->belongsTo(Cliente::class); }
    public function usuario(): BelongsTo { return $this->belongsTo(User::class); }
    public function pedido(): BelongsTo { return $this->belongsTo(PedidoVenta::class, "pedido_id"); }
    public function detalles(): HasMany { return $this->hasMany(DetallePresupuesto::class); }

    public function getVencidoAttribute(): bool
    {
        return $this->fecha_validez && $this->fecha_validez->isPast() && $this->estado === "pendiente";
    }
}
