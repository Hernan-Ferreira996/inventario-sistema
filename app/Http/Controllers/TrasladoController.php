<?php
namespace App\Http\Controllers;

use App\Models\TrasladoStock;
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
        $ubicaciones = Ubicacion::where("activo", true)->orderBy("nombre")->get();
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
            ]);

            foreach ($request->productos as $p) {
                $traslado->detalles()->create([
                    "producto_id" => $p["producto_id"],
                    "cantidad"    => $p["cantidad"],
                ]);

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

                MovimientoStock::create([
                    "producto_id"      => $p["producto_id"],
                    "ubicacion_id"     => $request->ubicacion_destino_id,
                    "usuario_id"       => Auth::id(),
                    "cantidad"         => abs($p["cantidad"]),
                    "tipo"             => "traslado",
                    "referencia"       => "Traslado #{$traslado->id}",
                    "notas"            => "Entrada desde almacén origen",
                    "fecha_movimiento" => $request->fecha_traslado,
                ]);
            }

            return $traslado;
        });

        return redirect()->route("traslados.show", $traslado)->with("success", "Traslado registrado correctamente.");
    }

    public function show(TrasladoStock $traslado)
    {
        $traslado->load(["ubicacionOrigen", "ubicacionDestino", "usuario", "detalles.producto"]);
        return view("traslados.detalle", compact("traslado"));
    }
}
