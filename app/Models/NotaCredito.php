<?php
namespace App\Models;

use App\Traits\PerteneceAEmpresa;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class NotaCredito extends Model
{
    use PerteneceAEmpresa;
    use LogsActivity;

    protected $table = "notas_credito";
    protected $fillable = [
        "factura_id", "usuario_id", "numero_documento", "timbrado",
        "establecimiento", "punto_expedicion", "cdc", "modo",
        "fecha_emision", "motivo", "descripcion_motivo",
        "subtotal", "impuesto_total", "total",
    ];
    protected $casts = [
        "fecha_emision" => "date",
        "subtotal" => "decimal:2", "impuesto_total" => "decimal:2", "total" => "decimal:2",
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(["numero_documento", "total", "motivo"])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName("notas_credito");
    }

    public function factura(): BelongsTo { return $this->belongsTo(Factura::class); }
    public function usuario(): BelongsTo { return $this->belongsTo(User::class); }
    public function detalles(): HasMany { return $this->hasMany(DetalleNotaCredito::class); }

    public function getNumeroCompletoAttribute(): string
    {
        return "{$this->establecimiento}-{$this->punto_expedicion}-{$this->numero_documento}";
    }
}
