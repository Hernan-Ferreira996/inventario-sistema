<?php
namespace App\Http\Controllers;
use App\Models\PedidoCompra;
use App\Models\DetallePedidoCompra;
use App\Models\MovimientoStock;
use App\Models\Proveedor;
use App\Models\Producto;
use App\Models\Ubicacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PedidoCompraController extends Controller
{
    public function index(Request $request)
    {
        $query = PedidoCompra::with(["proveedor","usuario"]);

        if ($request->filled("q")) {
            $q = $request->q;
            $query->where(function ($w) use ($q) {
                $w->where("numero_referencia", "like", "%{$q}%")
                  ->orWhereHas("proveedor", fn($p) => $p->where("nombre", "like", "%{$q}%"));
            });
        }
        if ($request->filled("estado")) {
            $query->where("estado", $request->estado);
        }

        $pedidos = $query->latest()->paginate(20)->withQueryString();
        return view("compras.lista", compact("pedidos"));
    }

    public function create()
    {
        $proveedores   = Proveedor::where("activo", true)->orderBy("nombre")->get();
        $productos     = Producto::where("activo", true)->orderBy("nombre")->get();
        $ubicaciones   = Ubicacion::where("activo", true)->orderBy("nombre")->get();
        $proximoNumero = $this->generarNumero();
        return view("compras.crear", compact("proveedores","productos","ubicaciones","proximoNumero"));
    }

    public function store(Request $request)
    {
        $request->validate([
            "proveedor_id"               => "required|exists:proveedores,id",
            "fecha_pedido"               => "required|date",
            "productos"                  => "required|array|min:1",
            "productos.*.producto_id"    => "required|exists:productos,id",
            "productos.*.cantidad"       => "required|numeric|min:0.01",
            "productos.*.precio_unitario"=> "required|numeric|min:0",
        ]);

        DB::transaction(function () use ($request) {
            $total = 0;
            foreach ($request->productos as $item) {
                $total += $item["cantidad"] * $item["precio_unitario"];
            }
            $pedido = PedidoCompra::create([
                "proveedor_id"      => $request->proveedor_id,
                "usuario_id"        => Auth::id(),
                "ubicacion_id"      => $request->ubicacion_id ?: null,
                "numero_referencia" => $this->generarNumero(),
                "comentarios"       => $request->comentarios,
                "fecha_pedido"      => $request->fecha_pedido,
                "fecha_esperada"    => $request->fecha_esperada ?: null,
                "total"             => $total,
                "estado"            => "pendiente",
            ]);
            foreach ($request->productos as $item) {
                DetallePedidoCompra::create([
                    "pedido_compra_id"  => $pedido->id,
                    "producto_id"       => $item["producto_id"],
                    "cantidad"          => $item["cantidad"],
                    "precio_unitario"   => $item["precio_unitario"],
                    "subtotal"          => $item["cantidad"] * $item["precio_unitario"],
                    "cantidad_recibida" => 0,
                ]);
            }
        });
        return redirect()->route("compras.index")->with("success","Pedido de compra registrado.");
    }

    public function show(PedidoCompra $pedidoCompra)
    {
        $pedidoCompra->load(["proveedor","usuario","ubicacion","detalles.producto","recepciones.detalles.producto"]);
        $ubicaciones = Ubicacion::where("activo", true)->orderBy("nombre")->get();
        return view("compras.detalle", compact("pedidoCompra","ubicaciones"));
    }

    public function edit(PedidoCompra $pedidoCompra)
    {
        if ($pedidoCompra->estado === "completado") {
            return redirect()->route("compras.show",$pedidoCompra)->with("error","No se puede editar un pedido completado.");
        }
        $proveedores = Proveedor::where("activo",true)->orderBy("nombre")->get();
        $ubicaciones = Ubicacion::where("activo",true)->orderBy("nombre")->get();
        $pedidoCompra->load("detalles.producto");
        return view("compras.editar", compact("pedidoCompra","proveedores","ubicaciones"));
    }

    public function update(Request $request, PedidoCompra $pedidoCompra)
    {
        $request->validate([
            "proveedor_id" => "required|exists:proveedores,id",
            "fecha_pedido" => "required|date",
            "estado"       => "required|in:pendiente,parcial,completado,cancelado",
        ]);
        $pedidoCompra->update([
            "proveedor_id"   => $request->proveedor_id,
            "ubicacion_id"   => $request->ubicacion_id ?: null,
            "fecha_pedido"   => $request->fecha_pedido,
            "fecha_esperada" => $request->fecha_esperada ?: null,
            "comentarios"    => $request->comentarios,
            "estado"         => $request->estado,
        ]);
        return redirect()->route("compras.show",$pedidoCompra)->with("success","Pedido actualizado.");
    }

    public function destroy(PedidoCompra $pedidoCompra)
    {
        if ($pedidoCompra->estado !== "pendiente") {
            return redirect()->route("compras.index")->with("error","Solo se pueden eliminar pedidos pendientes.");
        }
        $pedidoCompra->delete();
        return redirect()->route("compras.index")->with("success","Pedido eliminado.");
    }

    public function recibirStock(Request $request, PedidoCompra $pedidoCompra)
    {
        $request->validate([
            "ubicacion_id"       => "required|exists:ubicaciones,id",
            "items"              => "required|array|min:1",
            "items.*.detalle_id" => "required|exists:detalle_pedidos_compra,id",
            "items.*.cantidad"   => "required|numeric|min:0.01",
        ]);

        DB::transaction(function () use ($request, $pedidoCompra) {
            $recepcion = $pedidoCompra->recepciones()->create([
                "usuario_id"        => Auth::id(),
                "fecha_recepcion"   => now()->toDateString(),
                "numero_referencia" => $request->referencia,
                "notas"             => $request->notas,
            ]);

            foreach ($request->items as $item) {
                $detalle   = DetallePedidoCompra::findOrFail($item["detalle_id"]);
                $pendiente = $detalle->cantidad - $detalle->cantidad_recibida;
                $cantidad  = min((float) $item["cantidad"], $pendiente);
                if ($cantidad <= 0) continue;

                $recepcion->detalles()->create([
                    "producto_id"  => $detalle->producto_id,
                    "ubicacion_id" => $request->ubicacion_id,
                    "cantidad"     => $cantidad,
                ]);
                $detalle->increment("cantidad_recibida", $cantidad);
                MovimientoStock::create([
                    "producto_id"      => $detalle->producto_id,
                    "ubicacion_id"     => $request->ubicacion_id,
                    "usuario_id"       => Auth::id(),
                    "cantidad"         => $cantidad,
                    "tipo"             => "entrada",
                    "referencia"       => $pedidoCompra->numero_referencia,
                    "notas"            => "Recepcion de compra",
                    "fecha_movimiento" => now(),
                ]);
            }

            $pedidoCompra->load("detalles");
            $completo = $pedidoCompra->detalles->every(fn($d) => $d->cantidad_recibida >= $d->cantidad);
            $parcial  = $pedidoCompra->detalles->some(fn($d) => $d->cantidad_recibida > 0);
            $pedidoCompra->update(["estado" => $completo ? "completado" : ($parcial ? "parcial" : "pendiente")]);
        });

        return redirect()->route("compras.show",$pedidoCompra)->with("success","Stock recibido y registrado.");
    }

    private function generarNumero(): string
    {
        $ultimo = PedidoCompra::max("id") ?? 0;
        return "PC-" . str_pad($ultimo + 1, 6, "0", STR_PAD_LEFT);
    }
}