<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Empresa extends Model
{
    protected $table = "empresas";

    protected $fillable = [
        "nombre", "nombre_fantasia", "ruc", "dv", "telefono", "email", "web",
        "direccion", "ciudad", "pais", "moneda", "simbolo",
        "fact_timbrado", "fact_fecha_inicio_vigencia", "fact_establecimiento",
        "fact_punto_expedicion", "fact_modo",
        "timezone", "decimales", "stock_minimo", "activo",
    ];

    protected $casts = [
        "fact_fecha_inicio_vigencia" => "date",
        "activo" => "boolean",
    ];

    public function sucursales(): HasMany
    {
        return $this->hasMany(Sucursal::class);
    }

    public function usuarios(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function getRucCompletoAttribute(): string
    {
        return $this->ruc ? "{$this->ruc}-{$this->dv}" : "";
    }
}
