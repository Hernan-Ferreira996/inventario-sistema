<?php
namespace App\Http\Controllers;
use App\Models\CampoPersonalizado;
use App\Models\CatalogoValor;
use App\Models\SecuenciaDocumento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ConfiguracionController extends Controller
{
    private const GRUPOS_CATALOGO = [
        'pedidos_venta.estado', 'pedidos_venta.estado_factura', 'facturas.estado',
        'envios.estado', 'pedidos_compra.estado', 'notas_credito.motivo',
        'notas_remision.motivo', 'presupuestos.estado', 'clientes.tipo_precio',
    ];
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
        $empresaId = auth()->user()->empresa_id;

        $valoresCatalogo = CatalogoValor::where(function ($q) use ($empresaId) {
                $q->whereNull('empresa_id')->orWhere('empresa_id', $empresaId);
            })
            ->orderBy('grupo')->orderBy('orden')->get()->groupBy('grupo');

        $secuencias = SecuenciaDocumento::where('empresa_id', $empresaId)
            ->with('sucursal')->orderBy('tipo_documento')->get();

        $camposPersonalizados = CampoPersonalizado::where('empresa_id', $empresaId)
            ->orderBy('entidad')->orderBy('orden')->get()->groupBy('entidad');

        return view("configuracion.index", compact(
            "config", "valoresCatalogo", "secuencias", "camposPersonalizados"
        ) + ['gruposCatalogo' => self::GRUPOS_CATALOGO]);
    }

    public function storeCatalogo(Request $request)
    {
        $request->validate([
            'grupo'    => 'required|string|max:60',
            'codigo'   => 'required|string|max:60|alpha_dash',
            'etiqueta' => 'required|string|max:100',
            'color'       => 'nullable|string|max:20',
            'color_texto' => 'nullable|string|max:20',
        ]);

        $empresaId = auth()->user()->empresa_id;
        $siguienteOrden = 1 + (int) CatalogoValor::where('grupo', $request->grupo)->max('orden');

        CatalogoValor::create([
            'empresa_id'  => $empresaId,
            'grupo'       => $request->grupo,
            'codigo'      => $request->codigo,
            'etiqueta'    => $request->etiqueta,
            'color'       => $request->color ?: '#94a3b8',
            'color_texto' => $request->color_texto ?: '#ffffff',
            'orden'       => $siguienteOrden,
            'activo'      => true,
            'protegido'   => false,
        ]);

        return redirect()->route('configuracion.index', ['tab' => 'catalogos'])->with('success', 'Valor agregado al catálogo.');
    }

    public function toggleCatalogo(CatalogoValor $catalogoValor)
    {
        $catalogoValor->update(['activo' => !$catalogoValor->activo]);
        return redirect()->route('configuracion.index', ['tab' => 'catalogos'])->with('success', 'Estado actualizado.');
    }

    public function destroyCatalogo(CatalogoValor $catalogoValor)
    {
        if ($catalogoValor->protegido) {
            return redirect()->route('configuracion.index', ['tab' => 'catalogos'])->with('error', 'No se puede eliminar un valor del sistema. Podés desactivarlo.');
        }
        abort_unless($catalogoValor->empresa_id === auth()->user()->empresa_id, 403);
        $catalogoValor->delete();
        return redirect()->route('configuracion.index', ['tab' => 'catalogos'])->with('success', 'Valor eliminado.');
    }

    public function updateSecuencia(Request $request, SecuenciaDocumento $secuencia)
    {
        abort_unless($secuencia->empresa_id === auth()->user()->empresa_id, 403);

        $request->validate([
            'prefijo'        => 'nullable|string|max:20',
            'longitud'       => 'required|integer|min:1|max:20',
            'proximo_numero' => 'required|integer|min:1',
            'reinicio'       => 'required|in:nunca,anual',
        ]);

        $secuencia->update($request->only('prefijo', 'longitud', 'proximo_numero', 'reinicio'));

        return redirect()->route('configuracion.index', ['tab' => 'numeracion'])->with('success', 'Secuencia actualizada.');
    }

    public function storeCampoPersonalizado(Request $request)
    {
        $empresaId = auth()->user()->empresa_id;

        $request->validate([
            'entidad'   => 'required|in:cliente,proveedor',
            'nombre'    => [
                'required', 'string', 'max:60', 'alpha_dash',
                \Illuminate\Validation\Rule::unique('campos_personalizados')
                    ->where(fn($q) => $q->where('empresa_id', $empresaId)->where('entidad', $request->entidad)),
            ],
            'etiqueta'  => 'required|string|max:100',
            'tipo'      => 'required|in:texto,numero,fecha,booleano,select',
            'opciones'  => 'nullable|string',
        ]);

        $opciones = $request->tipo === 'select' && $request->filled('opciones')
            ? array_values(array_filter(array_map('trim', explode(',', $request->opciones))))
            : null;

        $siguienteOrden = 1 + (int) CampoPersonalizado::where('empresa_id', $empresaId)->where('entidad', $request->entidad)->max('orden');

        CampoPersonalizado::create([
            'empresa_id' => $empresaId,
            'entidad'    => $request->entidad,
            'nombre'     => $request->nombre,
            'etiqueta'   => $request->etiqueta,
            'tipo'       => $request->tipo,
            'opciones'   => $opciones,
            'requerido'  => $request->boolean('requerido'),
            'orden'      => $siguienteOrden,
            'activo'     => true,
        ]);

        return redirect()->route('configuracion.index', ['tab' => 'campos'])->with('success', 'Campo personalizado creado.');
    }

    public function toggleCampoPersonalizado(CampoPersonalizado $campoPersonalizado)
    {
        abort_unless($campoPersonalizado->empresa_id === auth()->user()->empresa_id, 403);
        $campoPersonalizado->update(['activo' => !$campoPersonalizado->activo]);
        return redirect()->route('configuracion.index', ['tab' => 'campos'])->with('success', 'Estado actualizado.');
    }

    public function destroyCampoPersonalizado(CampoPersonalizado $campoPersonalizado)
    {
        abort_unless($campoPersonalizado->empresa_id === auth()->user()->empresa_id, 403);
        $campoPersonalizado->delete();
        return redirect()->route('configuracion.index', ['tab' => 'campos'])->with('success', 'Campo eliminado.');
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
