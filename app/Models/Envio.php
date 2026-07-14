<?php
namespace App\Models;

use App\Traits\PerteneceAEmpresa;
use App\Traits\RestringidoPorSucursal;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Envio extends Model
{
    use PerteneceAEmpresa;
    use RestringidoPorSucursal;
    use LogsActivity;

    protected $table = "envios";
    protected $fillable = [
        "pedido_id", "numero_envio", "fecha_empaque", "fecha_entrega", "comentarios", "estado",
        "transportista", "chofer", "vehiculo_placa",
    ];
    protected $casts = ["fecha_empaque" => "date", "fecha_entrega" => "date"];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(["numero_envio", "estado"])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName("envios");
    }

    protected static function booted(): void
    {
        static::creating(function (self $model) {
            if (empty($model->sucursal_id) && $model->pedido_id) {
                $pedido = PedidoVenta::find($model->pedido_id);
                $model->sucursal_id ??= $pedido?->sucursal_id;
                $model->empresa_id ??= $pedido?->empresa_id;
            }
        });
    }

    public function pedido(): BelongsTo { return $this->belongsTo(PedidoVenta::class, "pedido_id"); }
    public function detalles(): HasMany { return $this->hasMany(DetalleEnvio::class); }
}
