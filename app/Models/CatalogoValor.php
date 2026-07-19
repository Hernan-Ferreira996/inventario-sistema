<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class CatalogoValor extends Model
{
    /**
     * Mapea el prefijo de "grupo" (antes del primer punto) al módulo del sistema
     * al que pertenece, para mostrarlo en la pantalla de administración de catálogos.
     */
    private const MODULOS = [
        'pedidos_venta'     => 'Comercial',
        'presupuestos'      => 'Comercial',
        'clientes'          => 'Comercial',
        'pedidos_compra'    => 'Compras',
        'facturas'          => 'Documentos',
        'notas_credito'     => 'Documentos',
        'notas_remision'    => 'Documentos',
        'envios'            => 'Documentos',
        'interacciones'     => 'CRM',
        'cuentas_contables' => 'Contabilidad',
    ];

    public static function modulo(string $grupo): string
    {
        $prefijo = explode('.', $grupo)[0];
        return self::MODULOS[$prefijo] ?? ucfirst(str_replace('_', ' ', $prefijo));
    }

    protected $table = 'catalogo_valores';

    protected $fillable = [
        'empresa_id', 'grupo', 'codigo', 'etiqueta', 'color', 'color_texto', 'orden', 'activo', 'protegido',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'protegido' => 'boolean',
    ];

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    /**
     * Valores activos de un grupo para la empresa actual: los globales (empresa_id null)
     * más los propios de la empresa, combinados y ordenados.
     */
    public static function paraGrupo(string $grupo, ?int $empresaId = null): \Illuminate\Support\Collection
    {
        $empresaId ??= Auth::check() ? Auth::user()->empresa_id : null;

        return static::where('grupo', $grupo)
            ->where('activo', true)
            ->where(function ($q) use ($empresaId) {
                $q->whereNull('empresa_id');
                if ($empresaId !== null) {
                    $q->orWhere('empresa_id', $empresaId);
                }
            })
            ->orderBy('orden')
            ->get()
            ->unique('codigo');
    }

    public static function codigos(string $grupo, ?int $empresaId = null): array
    {
        return static::paraGrupo($grupo, $empresaId)->pluck('codigo')->all();
    }

    public static function etiqueta(string $grupo, string $codigo, ?int $empresaId = null): string
    {
        $valor = static::paraGrupo($grupo, $empresaId)->firstWhere('codigo', $codigo);
        return $valor->etiqueta ?? ucfirst(str_replace('_', ' ', $codigo));
    }

    public static function colores(string $grupo, string $codigo, ?int $empresaId = null): array
    {
        $valor = static::paraGrupo($grupo, $empresaId)->firstWhere('codigo', $codigo);
        return [
            'color' => $valor->color ?? '#94a3b8',
            'color_texto' => $valor->color_texto ?? '#ffffff',
        ];
    }
}
