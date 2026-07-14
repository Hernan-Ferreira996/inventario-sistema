<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        "plan_id", "fecha_vencimiento_licencia", "max_usuarios",
    ];

    protected $casts = [
        "fact_fecha_inicio_vigencia" => "date",
        "fecha_vencimiento_licencia" => "date",
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

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function empresaModulos(): HasMany
    {
        return $this->hasMany(EmpresaModulo::class);
    }

    public function getRucCompletoAttribute(): string
    {
        return $this->ruc ? "{$this->ruc}-{$this->dv}" : "";
    }

    public function tieneModulo(string $codigoModulo): bool
    {
        if ($this->fecha_vencimiento_licencia !== null && $this->fecha_vencimiento_licencia->isPast()) {
            return false;
        }

        $modulo = Modulo::where('codigo', $codigoModulo)->first();
        if (!$modulo) {
            return false;
        }
        if ($modulo->nucleo) {
            return true;
        }

        $excepcion = $this->empresaModulos()->where('modulo_id', $modulo->id)->first();
        if ($excepcion) {
            if (!$excepcion->habilitado) {
                return false;
            }
            if (!$excepcion->fecha_vencimiento || !$excepcion->fecha_vencimiento->isPast()) {
                return true;
            }
        }

        if (!$this->plan_id) {
            return false;
        }

        return $this->plan->modulos()->where('modulos.id', $modulo->id)->exists();
    }
}
