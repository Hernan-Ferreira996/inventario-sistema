<?php

namespace App\Models;

use App\Traits\PerteneceAEmpresa;
use App\Traits\TieneCamposPersonalizados;
use App\Traits\TieneContactos;
use App\Traits\TieneDocumentosAdjuntos;
use App\Traits\TieneEtiquetas;
use App\Traits\TieneInteracciones;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Cliente extends Model
{
    use PerteneceAEmpresa;
    use TieneCamposPersonalizados;
    use TieneContactos;
    use TieneInteracciones;
    use TieneEtiquetas;
    use TieneDocumentosAdjuntos;
    use SoftDeletes, LogsActivity;

    public static function entidadCamposPersonalizados(): string
    {
        return 'cliente';
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nombre', 'email', 'telefono', 'tipo_precio', 'activo'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('clientes');
    }

    protected $table = 'clientes';

    protected $fillable = [
        'nombre', 'email', 'telefono', 'direccion', 'ciudad_id',
        'ruc_nit', 'tipo_precio', 'activo', 'limite_credito',
        'expuesto_publicamente', 'funcionario', 'exento_iva',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'activo' => 'boolean',
        'limite_credito' => 'decimal:2',
        'expuesto_publicamente' => 'boolean',
        'funcionario' => 'boolean',
        'exento_iva' => 'boolean',
    ];

    public function pedidos(): HasMany
    {
        return $this->hasMany(PedidoVenta::class);
    }

    public function ciudad(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Ciudad::class);
    }

    /**
     * Nivel de moroso calculado a partir de la factura vencida más antigua
     * con saldo pendiente (pendiente o parcial). No se persiste: se recalcula
     * en cada consulta a partir del historial real de facturas.
     */
    public function nivelMoroso(): string
    {
        $facturaVencida = Factura::whereHas('pedido', fn($q) => $q->where('cliente_id', $this->id))
            ->whereIn('estado', ['pendiente', 'parcial'])
            ->whereNotNull('fecha_vencimiento')
            ->whereDate('fecha_vencimiento', '<', now())
            ->orderBy('fecha_vencimiento')
            ->first();

        if (!$facturaVencida) {
            return 'al_dia';
        }

        $diasVencida = (int) abs(now()->diffInDays($facturaVencida->fecha_vencimiento));

        return match (true) {
            $diasVencida <= 30 => 'leve',
            $diasVencida <= 60 => 'moderado',
            default => 'grave',
        };
    }
}
