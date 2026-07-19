<?php
namespace App\Models;

use App\Traits\PerteneceAEmpresa;
use App\Traits\RestringidoPorSucursal;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Pago extends Model
{
    use PerteneceAEmpresa;
    use RestringidoPorSucursal;
    use LogsActivity;

    protected $table = "pagos";
    protected $fillable = [
        "pedido_id", "factura_id", "usuario_id", "metodo_pago_id", "monto", "fecha_pago", "referencia", "notas",
        "caja_id", "cobrador_id", "numero_recibo", "rendicion_id",
    ];
    protected $casts = ["monto" => "decimal:2", "fecha_pago" => "date"];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(["monto"])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName("pagos");
    }

    protected static function booted(): void
    {
        static::creating(function (self $model) {
            if (empty($model->sucursal_id)) {
                $model->sucursal_id = $model->factura_id
                    ? Factura::find($model->factura_id)?->sucursal_id
                    : PedidoVenta::find($model->pedido_id)?->sucursal_id;
            }
        });

        static::deleting(function (self $pago) {
            $asiento = AsientoContable::buscarPorOrigen($pago);
            if ($asiento) {
                AsientoContable::revertir($asiento, "Pago #{$pago->id} eliminado");
            }
        });
    }

    public function pedido(): BelongsTo { return $this->belongsTo(PedidoVenta::class, "pedido_id"); }
    public function factura(): BelongsTo { return $this->belongsTo(Factura::class); }
    public function usuario(): BelongsTo { return $this->belongsTo(User::class); }
    public function metodoPago(): BelongsTo { return $this->belongsTo(MetodoPago::class); }
    public function caja(): BelongsTo { return $this->belongsTo(Caja::class); }
    public function cobrador(): BelongsTo { return $this->belongsTo(User::class, "cobrador_id"); }
    public function rendicion(): BelongsTo { return $this->belongsTo(Rendicion::class); }
}
