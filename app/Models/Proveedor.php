<?php

namespace App\Models;

use App\Traits\PerteneceAEmpresa;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Proveedor extends Model
{
    use PerteneceAEmpresa;
    use SoftDeletes, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nombre', 'email', 'telefono', 'activo'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('proveedores');
    }

    protected $table = 'proveedores';

    protected $fillable = [
        'nombre', 'email', 'telefono', 'direccion',
        'ruc_nit', 'contacto', 'activo',
    ];

    protected $casts = ['activo' => 'boolean'];

    public function pedidosCompra(): HasMany
    {
        return $this->hasMany(PedidoCompra::class);
    }
}
