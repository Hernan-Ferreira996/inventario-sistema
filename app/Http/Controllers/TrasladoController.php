<?php
namespace App\Http\Controllers;

use App\Models\TrasladoStock;
use App\Models\DetalleTraslado;
use App\Models\Producto;
use App\Models\Ubicacion;
use App\Models\MovimientoStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TrasladoController extends Controller
{
    public function index()
    {
        $traslados = TrasladoStock::with(["ubicacionOrigen", "ubicacionDestino", "usuario"])->latest()->paginate(20);
        return view("traslados.lista", compact("traslados"));
    }

    public function create()
    {
        $ubicaciones = Ubicacion::where("activo", true)->visiblesPara(Auth::user())->orderBy("nombre")->get();
        $productos = Producto::activos()->orderBy("nombre")->get();
        return view("traslados.crear", compact("ubicaciones", "productos"));
    }

    public function store(Request $request)
    {
        $request->validate([
            "ubicacion_origen_id"  => "required|exists:ubicaciones,id|different:ubicacion_destino_id",
            "ubicacion_destino_id" => "required|exists:ubicaciones,id",
            "fecha_traslado"       => "required|date",
            "referencia"           => "nullable|string|max:100",
            "notas"                => "nullable|string",
            "productos"            => "required|array|min:1",
            "productos.*.producto_id" => "required|exists:productos,id",
            "productos.*.cantidad"    => "required|numeric|min:0.01",
        ]);

        Ubicacion::verificarAcceso(Auth::user(), (int) $request->ubicacion_origen_id);

        // Verificar stock disponible en origen para cada producto antes de trasladar
        foreach ($request->productos as $p) {
            $disponible = MovimientoStock::where("producto_id", $p["producto_id"])
                ->where("ubicacion_id", $request->ubicacion_origen_id)
                ->sum("cantidad");

            if ($disponible < $p["cantidad"]) {
                $producto = Producto::find($p["producto_id"]);
                return back()->withInput()->with("error",
                    "Stock insuficiente de '{$producto->nombre}' en el almacén de origen. Disponible: " . number_format($disponible, 2));
            }
        }

        $traslado = DB::transaction(function () use ($request) {
            $traslado = TrasladoStock::create([
                "usuario_id"           => Auth::id(),
                "ubicacion_origen_id"  => $request->ubicacion_origen_id,
                "ubicacion_destino_id" => $request->ubicacion_destino_id,
                "referencia"           => $request->referencia,
                "notas"                => $request->notas,
                "fecha_traslado"       => $request->fecha_traslado,
                "estado"               => "en_transito",
            ]);

            foreach ($request->productos as $p) {
                $traslado->detalles()->create([
                    "producto_id" => $p["producto_id"],
                    "cantidad"    => $p["cantidad"],
                ]);

                // Solo se descuenta el origen al despachar. El destino recién recibe
                // stock cuando se confirma la recepción (ver confirmarRecepcion()),
                // porque la cantidad recibida puede diferir de la enviada.
                MovimientoStock::create([
                    "producto_id"      => $p["producto_id"],
                    "ubicacion_id"     => $request->ubicacion_origen_id,
                    "usuario_id"       => Auth::id(),
                    "cantidad"         => -abs($p["cantidad"]),
                    "tipo"             => "traslado",
                    "referencia"       => "Traslado #{$traslado->id}",
                    "notas"            => "Salida hacia almacén destino",
                    "fecha_movimiento" => $request->fecha_traslado,
                ]);
            }

            return $traslado;
        });

        return redirect()->route("traslados.show", $traslado)->with("success", "Traslado registrado correctamente. Queda en tránsito hasta confirmar la recepción en destino.");
    }

    public function show(TrasladoStock $traslado)
    {
        $traslado->load(["ubicacionOrigen", "ubicacionDestino", "usuario", "usuarioRecepcion", "detalles.producto"]);
        return view("traslados.detalle", compact("traslado"));
    }

    public function confirmarRecepcion(TrasladoStock $traslado)
    {
        if ($traslado->estado !== "en_transito") {
            return redirect()->route("traslados.show", $traslado)->with("error", "Este traslado ya fue recibido.");
        }

        $traslado->load(["ubicacionOrigen", "ubicacionDestino", "detalles.producto"]);
        return view("traslados.recepcion", compact("traslado"));
    }

    public function guardarRecepcion(Request $request, TrasladoStock $traslado)
    {
        if ($traslado->estado !== "en_transito") {
            return redirect()->route("traslados.show", $traslado)->with("error", "Este traslado ya fue recibido.");
        }

        Ubicacion::verificarAcceso(Auth::user(), (int) $traslado->ubicacion_destino_id);

        $request->validate([
            "detalles"                  => "required|array|min:1",
            "detalles.*.id"             => "required|exists:detalle_traslados,id",
            "detalles.*.cantidad_recibida" => "required|numeric|min:0",
        ]);

        DB::transaction(function () use ($request, $traslado) {
            foreach ($request->detalles as $item) {
                $detalle = DetalleTraslado::where("traslado_id", $traslado->id)->findOrFail($item["id"]);
                $detalle->update(["cantidad_recibida" => $item["cantidad_recibida"]]);

                if ($item["cantidad_recibida"] > 0) {
                    MovimientoStock::create([
                        "producto_id"      => $detalle->producto_id,
                        "ubicacion_id"     => $traslado->ubicacion_destino_id,
                        "usuario_id"       => Auth::id(),
                        "cantidad"         => abs($item["cantidad_recibida"]),
                        "tipo"             => "traslado",
                        "referencia"       => "Traslado #{$traslado->id}",
                        "notas"            => "Entrada desde almacén origen (recepción confirmada)",
                        "fecha_movimiento" => now(),
                    ]);
                }
            }

            $traslado->update([
                "estado"                => "recibido",
                "fecha_recepcion"       => now()->toDateString(),
                "usuario_recepcion_id"  => Auth::id(),
            ]);
        });

        return redirect()->route("traslados.show", $traslado)->with("success", "Recepción confirmada correctamente.");
    }
}
