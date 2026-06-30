<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ConfiguracionController extends Controller
{
    private string $archivo = "configuracion.json";

    private function cargar(): array
    {
        if (!Storage::exists($this->archivo)) {
            return $this->defaults();
        }
        return array_merge($this->defaults(), json_decode(Storage::get($this->archivo), true) ?? []);
    }

    private function guardar(array $datos): void
    {
        Storage::put($this->archivo, json_encode($datos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    private function defaults(): array
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

    public function index()
    {
        $config = $this->cargar();
        return view("configuracion.index", compact("config"));
    }

    public function guardarEmpresa(Request $request)
    {
        $data = $request->validate([
            "empresa_nombre"          => "required|string|max:200",
            "empresa_nombre_fantasia" => "nullable|string|max:200",
            "empresa_ruc"             => "nullable|string|max:30",
            "empresa_dv"              => "nullable|string|max:2",
            "empresa_telefono"        => "nullable|string|max:30",
            "empresa_email"           => "nullable|email|max:150",
            "empresa_web"             => "nullable|string|max:150",
            "empresa_direccion"       => "nullable|string|max:255",
            "empresa_ciudad"          => "nullable|string|max:100",
            "empresa_pais"            => "nullable|string|max:100",
            "empresa_moneda"          => "nullable|string|max:10",
            "empresa_simbolo"         => "nullable|string|max:6",
        ]);

        $config = array_merge($this->cargar(), $data);
        $this->guardar($config);

        return redirect()->route("configuracion.index")->with("success", "Datos de empresa guardados correctamente.");
    }

    public function guardarSistema(Request $request)
    {
        $data = $request->validate([
            "sistema_timezone"     => "required|string|max:50",
            "sistema_decimales"    => "required|in:0,2,3",
            "sistema_stock_minimo" => "required|integer|min:0",
        ]);

        $config = array_merge($this->cargar(), $data);
        $this->guardar($config);

        return redirect()->route("configuracion.index")->with("success", "Configuracion del sistema guardada.");
    }

    public function guardarFacturacion(Request $request)
    {
        $data = $request->validate([
            "fact_timbrado"              => "nullable|string|max:20",
            "fact_fecha_inicio_vigencia" => "nullable|date",
            "fact_establecimiento"       => "required|string|max:3",
            "fact_punto_expedicion"      => "required|string|max:3",
            "fact_modo"                  => "required|in:local,electronico",
        ]);

        $config = array_merge($this->cargar(), $data);
        $this->guardar($config);

        return redirect()->route("configuracion.index")->with("success", "Configuracion de facturacion guardada.");
    }
}
