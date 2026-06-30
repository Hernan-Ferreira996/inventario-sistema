<?php
namespace App\Support;

use App\Models\Empresa;
use Illuminate\Support\Facades\Storage;

class Configuracion
{
    public static function obtener(): array
    {
        // Primero intentar leer desde la empresa del usuario autenticado
        if (auth()->check()) {
            $empresa = null;
            if (auth()->user()->empresa_id) {
                $empresa = Empresa::find(auth()->user()->empresa_id);
            } elseif (!auth()->user()->esSuperAdmin()) {
                $empresa = Empresa::first();
            }

            if ($empresa) {
                return self::fromEmpresa($empresa);
            }
        }

        // Fallback: leer del JSON (solo durante transición o para super-admin sin empresa)
        $defaults = self::defaults();
        if (Storage::exists("configuracion.json")) {
            return array_merge($defaults, json_decode(Storage::get("configuracion.json"), true) ?? []);
        }

        return $defaults;
    }

    public static function fromEmpresa(Empresa $empresa): array
    {
        return [
            "empresa_nombre"             => $empresa->nombre,
            "empresa_nombre_fantasia"    => $empresa->nombre_fantasia ?? "",
            "empresa_ruc"                => $empresa->ruc ?? "",
            "empresa_dv"                 => $empresa->dv ?? "",
            "empresa_telefono"           => $empresa->telefono ?? "",
            "empresa_email"              => $empresa->email ?? "",
            "empresa_web"                => $empresa->web ?? "",
            "empresa_direccion"          => $empresa->direccion ?? "",
            "empresa_ciudad"             => $empresa->ciudad ?? "",
            "empresa_pais"               => $empresa->pais ?? "Paraguay",
            "empresa_moneda"             => $empresa->moneda ?? "PYG",
            "empresa_simbolo"            => $empresa->simbolo ?? "Gs.",
            "sistema_timezone"           => $empresa->timezone ?? "America/Asuncion",
            "sistema_decimales"          => (string)($empresa->decimales ?? 0),
            "sistema_stock_minimo"       => (string)($empresa->stock_minimo ?? 5),
            "fact_timbrado"              => $empresa->fact_timbrado ?? "",
            "fact_fecha_inicio_vigencia" => $empresa->fact_fecha_inicio_vigencia?->format("Y-m-d") ?? "",
            "fact_establecimiento"       => $empresa->fact_establecimiento ?? "001",
            "fact_punto_expedicion"      => $empresa->fact_punto_expedicion ?? "001",
            "fact_modo"                  => $empresa->fact_modo ?? "local",
        ];
    }

    private static function defaults(): array
    {
        return [
            "empresa_nombre"          => "Mi Empresa",
            "empresa_nombre_fantasia" => "",
            "empresa_ruc"             => "",
            "empresa_dv"              => "",
            "empresa_telefono"        => "",
            "empresa_email"           => "",
            "empresa_web"             => "",
            "empresa_direccion"       => "",
            "empresa_ciudad"          => "",
            "empresa_pais"            => "Paraguay",
            "empresa_moneda"          => "PYG",
            "empresa_simbolo"         => "Gs.",
            "sistema_timezone"        => "America/Asuncion",
            "sistema_decimales"       => "0",
            "sistema_stock_minimo"    => "5",
            "fact_timbrado"           => "",
            "fact_fecha_inicio_vigencia" => "",
            "fact_establecimiento"    => "001",
            "fact_punto_expedicion"   => "001",
            "fact_modo"               => "local",
        ];
    }
}
