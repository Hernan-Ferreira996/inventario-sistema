<?php
namespace App\Models;

use App\Traits\PerteneceAEmpresa;
use App\Traits\RestringidoPorSucursal;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class NotaRemision extends Model
{
    use PerteneceAEmpresa;
    use RestringidoPorSucursal;
    use LogsActivity;

    protected $table = "notas_remision";
    protected $fillable = [
        "pedido_id", "envio_id", "usuario_id", "ubicacion_origen_id",
        "numero_documento", "timbrado", "establecimiento", "punto_expedicion", "cdc", "modo",
        "fecha_emision", "motivo", "direccion_destino", "transportista", "vehiculo_placa", "observaciones",
    ];
    protected $casts = ["fecha_emision" => "date"];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(["numero_documento", "motivo"])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName("notas_remision");
    }

    protected static function booted(): void
    {
        static::deleting(function (self $nota) {
            MovimientoStock::revertir($nota, "Nota de Remisión {$nota->numero_completo} eliminada");
        });
    }

    public function pedido(): BelongsTo { return $this->belongsTo(PedidoVenta::class, "pedido_id"); }
    public function envio(): BelongsTo { return $this->belongsTo(Envio::class); }
    public function usuario(): BelongsTo { return $this->belongsTo(User::class); }
    public function ubicacionOrigen(): BelongsTo { return $this->belongsTo(Ubicacion::class, "ubicacion_origen_id"); }
    public function detalles(): HasMany { return $this->hasMany(DetalleNotaRemision::class); }

    public function getNumeroCompletoAttribute(): string
    {
        return "{$this->establecimiento}-{$this->punto_expedicion}-{$this->numero_documento}";
    }
}
