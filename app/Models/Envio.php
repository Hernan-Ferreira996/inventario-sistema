<?php
namespace App\Models;

use App\Traits\PerteneceAEmpresa;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Envio extends Model
{
    use PerteneceAEmpresa;
    use LogsActivity;

    protected $table = "envios";
    protected $fillable = ["pedido_id", "numero_envio", "fecha_empaque", "fecha_entrega", "comentarios", "estado"];
    protected $casts = ["fecha_empaque" => "date", "fecha_entrega" => "date"];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(["numero_envio", "estado"])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName("envios");
    }

    public function pedido(): BelongsTo { return $this->belongsTo(PedidoVenta::class, "pedido_id"); }
    public function detalles(): HasMany { return $this->hasMany(DetalleEnvio::class); }
}
