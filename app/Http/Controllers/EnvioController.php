<?php
namespace App\Http\Controllers;

use App\Models\Envio;
use App\Models\NotaRemision;
use App\Models\PedidoVenta;
use App\Mail\EnvioNotificacionMail;
use App\Support\Numeracion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EnvioController extends Controller
{
    /**
     * Notifica por email al cliente del pedido sobre el estado del envío.
     * No interrumpe el flujo si el cliente no tiene email o si el envío
     * de correo falla (queda solo registrado en el log).
     */
    private function notificarCliente(Envio $envio): void
    {
        $cliente = $envio->pedido?->cliente;
        if (!$cliente || !$cliente->email) {
            return;
        }

        try {
            Mail::to($cliente->email)->send(new EnvioNotificacionMail($envio));
        } catch (\Throwable $e) {
            Log::warning("No se pudo notificar al cliente sobre el envío {$envio->numero_envio}: {$e->getMessage()}");
        }
    }
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
        $proximoNumero = Numeracion::previsualizar('envios', $pedido->sucursal_id, 'ENV-');

        // Si ya se generó una Nota de Remisión para este pedido, se reutilizan
        // sus datos de transporte para no cargarlos dos veces.
        $ultimaRemision = NotaRemision::where('pedido_id', $pedido->id)->latest()->first();

        return view("envios.crear", compact("pedido", "proximoNumero", "ultimaRemision"));
    }

    public function store(Request $request)
    {
        $request->validate([
            "pedido_id"      => "required|exists:pedidos_venta,id",
            "fecha_empaque"  => "required|date",
            "fecha_entrega"  => "nullable|date|after_or_equal:fecha_empaque",
            "estado"         => "required|in:" . implode(',', \App\Models\CatalogoValor::codigos('envios.estado')),
            "comentarios"    => "nullable|string",
            "transportista"  => "nullable|string|max:150",
            "chofer"         => "nullable|string|max:150",
            "vehiculo_placa" => "nullable|string|max:20",
            "productos"      => "required|array|min:1",
            "productos.*.producto_id" => "required|exists:productos,id",
            "productos.*.cantidad"    => "required|numeric|min:0.01",
        ]);

        $sucursalPedido = PedidoVenta::find($request->pedido_id)?->sucursal_id;
        $envio = Envio::create([
            "pedido_id"      => $request->pedido_id,
            "numero_envio"   => Numeracion::siguiente('envios', $sucursalPedido, 'ENV-'),
            "fecha_empaque"  => $request->fecha_empaque,
            "fecha_entrega"  => $request->fecha_entrega,
            "estado"         => $request->estado,
            "comentarios"    => $request->comentarios,
            "transportista"  => $request->transportista,
            "chofer"         => $request->chofer,
            "vehiculo_placa" => $request->vehiculo_placa,
        ]);

        foreach ($request->productos as $p) {
            $envio->detalles()->create([
                "producto_id" => $p["producto_id"],
                "cantidad"    => $p["cantidad"],
            ]);
        }

        $this->notificarCliente($envio->load('pedido.cliente'));

        return redirect()->route("envios.show", $envio)->with("success", "Envío registrado correctamente.");
    }

    public function show(Envio $envio)
    {
        $envio->load(["pedido.cliente", "detalles.producto"]);
        return view("envios.detalle", compact("envio"));
    }

    public function edit(Envio $envio)
    {
        $envio->load(["detalles.producto", "pedido.cliente"]);
        return view("envios.editar", compact("envio"));
    }

    public function update(Request $request, Envio $envio)
    {
        $request->validate([
            "fecha_empaque"  => "required|date",
            "fecha_entrega"  => "nullable|date|after_or_equal:fecha_empaque",
            "estado"         => "required|in:" . implode(',', \App\Models\CatalogoValor::codigos('envios.estado')),
            "comentarios"    => "nullable|string",
            "transportista"  => "nullable|string|max:150",
            "chofer"         => "nullable|string|max:150",
            "vehiculo_placa" => "nullable|string|max:20",
        ]);

        $cambioEstado = $envio->estado !== $request->estado;

        $envio->update($request->only([
            "fecha_empaque", "fecha_entrega", "estado", "comentarios",
            "transportista", "chofer", "vehiculo_placa",
        ]));

        if ($cambioEstado) {
            $this->notificarCliente($envio->load('pedido.cliente'));
        }

        return redirect()->route("envios.show", $envio)->with("success", "Envío actualizado.");
    }

    public function destroy(Envio $envio)
    {
        $envio->delete();
        return redirect()->route("envios.index")->with("success", "Envío eliminado.");
    }
}
