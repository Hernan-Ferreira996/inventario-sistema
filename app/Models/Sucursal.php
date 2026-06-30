<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sucursal extends Model
{
    protected $table = "sucursales";

    protected $fillable = [
        "empresa_id", "codigo", "nombre", "direccion",
        "ciudad", "telefono", "principal", "activo",
    ];

    protected $casts = [
        "principal" => "boolean",
        "activo" => "boolean",
    ];

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    public function depositos(): HasMany
    {
        return $this->hasMany(Ubicacion::class);
    }
}
