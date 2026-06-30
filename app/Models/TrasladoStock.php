<?php
namespace App\Models;

use App\Traits\PerteneceAEmpresa;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class TrasladoStock extends Model
{
    use PerteneceAEmpresa;
    use LogsActivity;

    protected $table = "traslados_stock";
    protected $fillable = ["usuario_id", "ubicacion_origen_id", "ubicacion_destino_id", "referencia", "notas", "fecha_traslado"];
    protected $casts = ["fecha_traslado" => "date"];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(["referencia"])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName("traslados");
    }

    public function usuario(): BelongsTo { return $this->belongsTo(User::class); }
    public function ubicacionOrigen(): BelongsTo { return $this->belongsTo(Ubicacion::class, "ubicacion_origen_id"); }
    public function ubicacionDestino(): BelongsTo { return $this->belongsTo(Ubicacion::class, "ubicacion_destino_id"); }
    public function detalles(): HasMany { return $this->hasMany(DetalleTraslado::class, "traslado_id"); }
}
