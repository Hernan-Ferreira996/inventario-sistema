<?php
namespace App\Http\Controllers;

use App\Models\Presupuesto;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\PedidoVenta;
use App\Support\Configuracion;
use App\Support\Numeracion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class PresupuestoController extends Controller
{
    public function index(Request $request)
    {
        $query = Presupuesto::with("cliente");

        if ($request->filled("q")) {
            $q = $request->q;
            $query->where(function ($w) use ($q) {
                $w->where("numero_documento", "like", "%{$q}%")
                  ->orWhereHas("cliente", fn($c) => $c->where("nombre", "like", "%{$q}%"));
            });
        }
        if ($request->filled("estado")) {
            $query->where("estado", $request->estado);
        }

        $presupuestos = $query->latest()->paginate(20)->withQueryString();

        return view("presupuestos.lista", compact("presupuestos"));
    }

    public function create()
    {
        $clientes  = Cliente::where("activo", true)->orderBy("nombre")->get();
        $productos = Producto::activos()->orderBy("nombre")->get();
        $proximoNumero = Numeracion::previsualizar('presupuestos', null, 'PRE-');

        return view("presupuestos.crear", compact("clientes", "productos", "proximoNumero"));
    }

    public function store(Request $request)
    {
        $request->validate([
            "cliente_id"      => "required|exists:clientes,id",
            "fecha_emision"   => "required|date",
            "fecha_validez"   => "nullable|date|after_or_equal:fecha_emision",
            "comentarios"     => "nullable|string",
            "productos"       => "required|array|min:1",
            "productos.*.producto_id"     => "required|exists:productos,id",
            "productos.*.cantidad"        => "required|numeric|min:0.01",
            "productos.*.precio_unitario" => "required|numeric|min:0",
        ]);

        $subtotal = collect($request->productos)->sum(fn($d) =>
            $d["cantidad"] * $d["precio_unitario"] * (1 - ($d["descuento"] ?? 0) / 100)
        );

        $presupuesto = DB::transaction(function () use ($request, $subtotal) {
            $presupuesto = Presupuesto::create([
                "cliente_id"       => $request->cliente_id,
                "usuario_id"       => Auth::id(),
                "numero_documento" => Numeracion::siguiente('presupuestos', null, 'PRE-'),
                "fecha_emision"    => $request->fecha_emision,
                "fecha_validez"    => $request->fecha_validez,
                "comentarios"      => $request->comentarios,
                "subtotal"         => $subtotal,
                "impuesto_total"   => 0,
                "total"            => $subtotal,
                "estado"           => "pendiente",
                "etapa"            => "prospecto",
            ]);

            foreach ($request->productos as $d) {
                $producto = Producto::find($d["producto_id"]);
                $presupuesto->detalles()->create([
                    "producto_id"     => $d["producto_id"],
                    "cantidad"        => $d["cantidad"],
                    "precio_unitario" => $d["precio_unitario"],
                    "descuento"       => $d["descuento"] ?? 0,
                    "impuesto"        => $producto->impuesto?->porcentaje ?? 0,
                    "subtotal"        => $d["cantidad"] * $d["precio_unitario"],
                ]);
            }

            return $presupuesto;
        });

        return redirect()->route("presupuestos.show", $presupuesto)
            ->with("success", "Presupuesto creado correctamente.");
    }

    public function show(Presupuesto $presupuesto)
    {
        $presupuesto->load(["cliente", "usuario", "detalles.producto", "pedido"]);
        return view("presupuestos.detalle", compact("presupuesto"));
    }

    public function pdf(Presupuesto $presupuesto)
    {
        $presupuesto->load(["cliente", "detalles.producto"]);
        $config = Configuracion::obtener();

        $pdf = Pdf::loadView("presupuestos.pdf", compact("presupuesto", "config"))->setPaper("a4");

        return $pdf->stream("presupuesto-{$presupuesto->numero_documento}.pdf");
    }

    public function edit(Presupuesto $presupuesto)
    {
        if ($presupuesto->estado === "convertido") {
            return redirect()->route("presupuestos.show", $presupuesto)
                ->with("error", "No se puede editar un presupuesto ya convertido en pedido.");
        }
        $presupuesto->load("detalles.producto");
        $clientes  = Cliente::where("activo", true)->orderBy("nombre")->get();
        $productos = Producto::activos()->orderBy("nombre")->get();

        return view("presupuestos.editar", compact("presupuesto", "clientes", "productos"));
    }

    public function update(Request $request, Presupuesto $presupuesto)
    {
        $request->validate([
            "cliente_id"    => "required|exists:clientes,id",
            "fecha_emision" => "required|date",
            "fecha_validez" => "nullable|date|after_or_equal:fecha_emision",
            // 'convertido' se excluye a propósito: es una transición de solo-sistema vía convertir(), no editable a mano
            "estado"        => "required|in:" . implode(',', array_diff(\App\Models\CatalogoValor::codigos('presupuestos.estado'), ['convertido'])),
            "etapa"         => "required|in:" . implode(',', \App\Models\CatalogoValor::codigos('presupuestos.etapa')),
            "comentarios"   => "nullable|string",
            "productos"     => "required|array|min:1",
            "productos.*.producto_id"     => "required|exists:productos,id",
            "productos.*.cantidad"        => "required|numeric|min:0.01",
            "productos.*.precio_unitario" => "required|numeric|min:0",
        ]);

        $subtotal = collect($request->productos)->sum(fn($d) =>
            $d["cantidad"] * $d["precio_unitario"] * (1 - ($d["descuento"] ?? 0) / 100)
        );

        $presupuesto->update([
            "cliente_id"     => $request->cliente_id,
            "fecha_emision"  => $request->fecha_emision,
            "fecha_validez"  => $request->fecha_validez,
            "estado"         => $request->estado,
            "etapa"          => $request->etapa,
            "comentarios"    => $request->comentarios,
            "subtotal"       => $subtotal,
            "total"          => $subtotal,
        ]);

        $presupuesto->detalles()->delete();
        foreach ($request->productos as $d) {
            $producto = Producto::find($d["producto_id"]);
            $presupuesto->detalles()->create([
                "producto_id"     => $d["producto_id"],
                "cantidad"        => $d["cantidad"],
                "precio_unitario" => $d["precio_unitario"],
                "descuento"       => $d["descuento"] ?? 0,
                "impuesto"        => $producto->impuesto?->porcentaje ?? 0,
                "subtotal"        => $d["cantidad"] * $d["precio_unitario"],
            ]);
        }

        return redirect()->route("presupuestos.show", $presupuesto)->with("success", "Presupuesto actualizado.");
    }

    public function destroy(Presupuesto $presupuesto)
    {
        if ($presupuesto->estado === "convertido") {
            return redirect()->route("presupuestos.index")
                ->with("error", "No se puede eliminar un presupuesto ya convertido en pedido.");
        }
        $presupuesto->delete();
        return redirect()->route("presupuestos.index")->with("success", "Presupuesto eliminado.");
    }

    public function convertirAPedido(Presupuesto $presupuesto)
    {
        if ($presupuesto->estado === "convertido" && $presupuesto->pedido_id) {
            return $presupuesto->pedido;
        }

        if ($presupuesto->estado !== "aprobado") {
            abort(422, "Solo se pueden convertir presupuestos aprobados.");
        }

        return DB::transaction(function () use ($presupuesto) {
            $presupuesto->load("detalles");

            $pedido = PedidoVenta::create([
                "cliente_id"         => $presupuesto->cliente_id,
                "usuario_id"         => Auth::id(),
                "numero_referencia"  => Numeracion::siguiente('pedidos_venta', $presupuesto->sucursal_id, 'PV-'),
                "comentarios"        => "Generado desde presupuesto {$presupuesto->numero_documento}",
                "fecha_pedido"       => now()->toDateString(),
                "total"              => $presupuesto->total,
                "estado"             => "activo",
                "estado_factura"     => "pendiente",
            ]);

            foreach ($presupuesto->detalles as $d) {
                $pedido->detalles()->create([
                    "producto_id"     => $d->producto_id,
                    "cantidad"        => $d->cantidad,
                    "precio_unitario" => $d->precio_unitario,
                    "descuento"       => $d->descuento,
                    "impuesto"        => $d->impuesto,
                    "subtotal"        => $d->subtotal,
                ]);
            }

            $presupuesto->update(["estado" => "convertido", "etapa" => "ganado", "pedido_id" => $pedido->id]);

            return $pedido;
        });
    }

    public function convertir(Presupuesto $presupuesto)
    {
        $pedido = $this->convertirAPedido($presupuesto);
        return redirect()->route("pedidos.show", $pedido)
            ->with("success", "Presupuesto convertido en pedido {$pedido->numero_referencia}.");
    }
}
