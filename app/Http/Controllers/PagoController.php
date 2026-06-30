<?php
namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Factura;
use App\Models\MetodoPago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PagoController extends Controller
{
    public function index(Request $request)
    {
        $query = Pago::with(["factura.pedido.cliente", "metodoPago"]);

        if ($request->filled("q")) {
            $q = $request->q;
            $query->whereHas("factura", fn($f) => $f->where("numero_factura", "like", "%{$q}%")
                ->orWhereHas("pedido.cliente", fn($c) => $c->where("nombre", "like", "%{$q}%")));
        }

        $pagos = $query->latest()->paginate(20)->withQueryString();
        return view("pagos.lista", compact("pagos"));
    }

    public function show(Pago $pago)
    {
        $pago->load(["factura.pedido.cliente", "metodoPago"]);
        return view("pagos.detalle", compact("pago"));
    }

    public function store(Request $request)
    {
        $request->validate([
            "factura_id"     => "required|exists:facturas,id",
            "metodo_pago_id" => "required|exists:metodos_pago,id",
            "monto"          => "required|numeric|min:0.01",
            "fecha_pago"     => "required|date",
            "referencia"     => "nullable|string|max:100",
            "notas"          => "nullable|string",
        ]);

        $factura = Factura::with('pedido')->findOrFail($request->factura_id);
        $saldo = $factura->total - $factura->monto_pagado;

        if ($request->monto > $saldo) {
            return back()->withInput()->with("error", "El monto excede el saldo pendiente de la factura (" . number_format($saldo, 0, ",", ".") . ").");
        }

        DB::transaction(function () use ($request, $factura) {
            Pago::create([
                "pedido_id"      => $factura->pedido_id,
                "factura_id"     => $factura->id,
                "usuario_id"     => \Illuminate\Support\Facades\Auth::id(),
                "metodo_pago_id" => $request->metodo_pago_id,
                "monto"          => $request->monto,
                "fecha_pago"     => $request->fecha_pago,
                "referencia"     => $request->referencia,
                "notas"          => $request->notas,
            ]);

            $factura->increment("monto_pagado", $request->monto);
            $factura->refresh();

            $factura->update([
                "estado" => $factura->monto_pagado >= $factura->total ? "pagada" : "parcial",
            ]);

            if ($factura->pedido) {
                $factura->pedido->increment("monto_pagado", $request->monto);
            }
        });

        return redirect()->route("facturas.show", $factura)->with("success", "Pago registrado correctamente.");
    }

    public function edit(Pago $pago)
    {
        $metodosPago = MetodoPago::where("activo", true)->orderBy("nombre")->get();
        return view("pagos.editar", compact("pago", "metodosPago"));
    }

    public function update(Request $request, Pago $pago)
    {
        $request->validate([
            "metodo_pago_id" => "required|exists:metodos_pago,id",
            "fecha_pago"     => "required|date",
            "referencia"     => "nullable|string|max:100",
            "notas"          => "nullable|string",
        ]);

        $pago->update($request->only(["metodo_pago_id", "fecha_pago", "referencia", "notas"]));

        return redirect()->route("pagos.show", $pago)->with("success", "Pago actualizado.");
    }

    public function destroy(Pago $pago)
    {
        DB::transaction(function () use ($pago) {
            $factura = $pago->factura;
            if ($factura) {
                $factura->decrement("monto_pagado", $pago->monto);
                $factura->refresh();
                $factura->update([
                    "estado" => $factura->monto_pagado <= 0 ? "pendiente" : ($factura->monto_pagado >= $factura->total ? "pagada" : "parcial"),
                ]);
                $factura->pedido?->decrement("monto_pagado", $pago->monto);
            }
            $pago->delete();
        });

        return redirect()->route("pagos.index")->with("success", "Pago eliminado y saldo restaurado.");
    }
}
