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
        'expuesto_publicamente', 'funcionario',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'activo' => 'boolean',
        'limite_credito' => 'decimal:2',
        'expuesto_publicamente' => 'boolean',
        'funcionario' => 'boolean',
    ];

    public function pedidos(): HasMany
    {
        return $this->hasMany(PedidoVenta::class);
    }

    public function ciudad(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Ciudad::class);
    }
}
