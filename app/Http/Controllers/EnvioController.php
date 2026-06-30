<?php
namespace App\Http\Controllers;

use App\Models\Envio;
use App\Models\PedidoVenta;
use Illuminate\Http\Request;

class EnvioController extends Controller
{
    public function index(Request $request)
    {
        $query = Envio::with("pedido.cliente");

        if ($request->filled("q")) {
            $q = $request->q;
            $query->where("numero_envio", "like", "%{$q}%")
                ->orWhereHas("pedido", fn($p) => $p->where("numero_referencia", "like", "%{$q}%")
                    ->orWhereHas("cliente", fn($c) => $c->where("nombre", "like", "%{$q}%")));
        }
        if ($request->filled("estado")) {
            $query->where("estado", $request->estado);
        }

        $envios = $query->latest()->paginate(20)->withQueryString();
        return view("envios.lista", compact("envios"));
    }

    public function create(Request $request)
    {
        $pedido = PedidoVenta::with(["cliente", "detalles.producto"])->findOrFail($request->pedido);
        $proximoNumero = "ENV-" . str_pad((Envio::count() + 1), 6, "0", STR_PAD_LEFT);
        return view("envios.crear", compact("pedido", "proximoNumero"));
    }

    public function store(Request $request)
    {
        $request->validate([
            "pedido_id"      => "required|exists:pedidos_venta,id",
            "fecha_empaque"  => "required|date",
            "fecha_entrega"  => "nullable|date|after_or_equal:fecha_empaque",
            "estado"         => "required|in:preparando,enviado,entregado,devuelto",
            "comentarios"    => "nullable|string",
            "productos"      => "required|array|min:1",
            "productos.*.producto_id" => "required|exists:productos,id",
            "productos.*.cantidad"    => "required|numeric|min:0.01",
        ]);

        $envio = Envio::create([
            "pedido_id"     => $request->pedido_id,
            "numero_envio"  => "ENV-" . str_pad((Envio::count() + 1), 6, "0", STR_PAD_LEFT),
            "fecha_empaque" => $request->fecha_empaque,
            "fecha_entrega" => $request->fecha_entrega,
            "estado"        => $request->estado,
            "comentarios"   => $request->comentarios,
        ]);

        foreach ($request->productos as $p) {
            $envio->detalles()->create([
                "producto_id" => $p["producto_id"],
                "cantidad"    => $p["cantidad"],
            ]);
        }

        return redirect()->route("envios.show", $envio)->with("success", "Envío registrado correctamente.");
    }

    public function show(Envio $envio)
    {
        $envio->load(["pedido.cliente", "detalles.producto"]);
        return view("envios.detalle", compact("envio"));
    }

    public function edit(Envio $envio)
    {
        $envio->load("detalles.producto");
        return view("envios.editar", compact("envio"));
    }

    public function update(Request $request, Envio $envio)
    {
        $request->validate([
            "fecha_empaque" => "required|date",
            "fecha_entrega" => "nullable|date|after_or_equal:fecha_empaque",
            "estado"        => "required|in:preparando,enviado,entregado,devuelto",
            "comentarios"   => "nullable|string",
        ]);

        $envio->update($request->only(["fecha_empaque", "fecha_entrega", "estado", "comentarios"]));

        return redirect()->route("envios.show", $envio)->with("success", "Envío actualizado.");
    }

    public function destroy(Envio $envio)
    {
        $envio->delete();
        return redirect()->route("envios.index")->with("success", "Envío eliminado.");
    }
}
