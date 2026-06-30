<?php
namespace App\Models;

use App\Traits\PerteneceAEmpresa;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Pago extends Model
{
    use PerteneceAEmpresa;
    use LogsActivity;

    protected $table = "pagos";
    protected $fillable = ["pedido_id", "factura_id", "usuario_id", "metodo_pago_id", "monto", "fecha_pago", "referencia", "notas"];
    protected $casts = ["monto" => "decimal:2", "fecha_pago" => "date"];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(["monto"])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName("pagos");
    }

    public function pedido(): BelongsTo { return $this->belongsTo(PedidoVenta::class, "pedido_id"); }
    public function factura(): BelongsTo { return $this->belongsTo(Factura::class); }
    public function usuario(): BelongsTo { return $this->belongsTo(User::class); }
    public function metodoPago(): BelongsTo { return $this->belongsTo(MetodoPago::class); }
}
