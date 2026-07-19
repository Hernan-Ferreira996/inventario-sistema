<?php
namespace App\Http\Controllers;

use App\Models\Factura;
use App\Models\PedidoVenta;
use App\Models\Presupuesto;
use App\Events\FacturaEmitida;
use App\Support\Configuracion;
use App\Support\Numeracion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class FacturaController extends Controller
{
    public function index(Request $request)
    {
        $query = Factura::with('pedido.cliente');

        if ($request->filled('cliente_id')) {
            $query->whereHas('pedido', fn($p) => $p->where('cliente_id', $request->cliente_id));
        }
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($w) use ($q) {
                $w->where('numero_factura', 'like', "%{$q}%")
                  ->orWhereHas('pedido.cliente', fn($c) => $c->where('nombre', 'like', "%{$q}%"));
            });
        }

        $facturas = $query->latest()->paginate(20)->withQueryString();
        $clienteFiltrado = $request->filled('cliente_id')
            ? \App\Models\Cliente::find($request->cliente_id)
            : null;

        return view('facturas.lista', compact('facturas', 'clienteFiltrado'));
    }

    public function create(Request $request)
    {
        $config = Configuracion::obtener();

        // Entrada desde un Presupuesto: si está aprobado, se convierte (o reutiliza)
        // el pedido asociado y se sigue el flujo normal de creación de factura.
        if ($request->filled('presupuesto')) {
            $presupuesto = Presupuesto::where('numero_documento', $request->presupuesto)->first();

            if (!$presupuesto) {
                return back()->withInput()->with('error', "No se encontró el presupuesto '{$request->presupuesto}'.");
            }
            if ($presupuesto->estado !== 'aprobado' && $presupuesto->estado !== 'convertido') {
                return back()->withInput()->with('error', "El presupuesto {$presupuesto->numero_documento} debe estar APROBADO para poder facturarlo (estado actual: {$presupuesto->estado}).");
            }

            $pedido = app(PresupuestoController::class)->convertirAPedido($presupuesto);

            return redirect()->route('facturas.create', ['pedido' => $pedido->id])
                ->with('success', "Datos del presupuesto {$presupuesto->numero_documento} cargados correctamente.");
        }

        // Sin pedido ni presupuesto: mostrar pantalla de selección
        if (!$request->filled('pedido')) {
            $pedidosPendientes = PedidoVenta::with('cliente')
                ->where('estado_factura', '!=', 'completado')
                ->latest()
                ->take(50)
                ->get();

            return view('facturas.seleccionar', compact('pedidosPendientes'));
        }

        $pedido = PedidoVenta::with(['cliente', 'detalles.producto'])->findOrFail($request->pedido);
        $proximoNumero = Numeracion::previsualizar('facturas', $pedido->sucursal_id);

        return view('facturas.crear', compact('pedido', 'config', 'proximoNumero'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pedido_id'        => 'required|exists:pedidos_venta,id',
            'condicion_venta'  => 'required|in:contado,credito',
            'tipo_documento_cliente'   => 'nullable|string|max:5',
            'numero_documento_cliente' => 'nullable|string|max:20',
            'descuento_global' => 'nullable|numeric|min:0|max:100',
        ]);

        $pedido = PedidoVenta::with('detalles')->findOrFail($request->pedido_id);
        $config = Configuracion::obtener();

        $subtotal = $pedido->detalles->sum('subtotal');
        $descuentoGlobal = (float) ($request->descuento_global ?? 0);
        $montoDescuento  = round($subtotal * $descuentoGlobal / 100);
        $subtotalConDesc = $subtotal - $montoDescuento;
        $impuestoTotal   = $pedido->detalles->sum(fn($d) => $d->subtotal * ($d->impuesto / 100));
        // El impuesto se calcula sobre el subtotal con descuento aplicado
        if ($descuentoGlobal > 0) {
            $impuestoTotal = $pedido->detalles->sum(fn($d) => ($d->subtotal * (1 - $descuentoGlobal / 100)) * ($d->impuesto / 100));
        }

        $factura = DB::transaction(function () use ($request, $pedido, $config, $subtotal, $subtotalConDesc, $impuestoTotal, $descuentoGlobal, $montoDescuento) {
            $factura = Factura::create([
                'pedido_id'         => $pedido->id,
                'numero_factura'    => Numeracion::siguiente('facturas', $pedido->sucursal_id),
                'fecha_factura'     => now()->toDateString(),
                'fecha_vencimiento' => $request->condicion_venta === 'credito' ? now()->addDays(30)->toDateString() : null,
                'timbrado'          => $config['fact_timbrado'],
                'establecimiento'   => $config['fact_establecimiento'],
                'punto_expedicion'  => $config['fact_punto_expedicion'],
                'modo'              => $config['fact_modo'],
                'tipo_documento_cliente'   => $request->tipo_documento_cliente,
                'numero_documento_cliente' => $request->numero_documento_cliente,
                'condicion_venta'   => $request->condicion_venta,
                'descuento_global'  => $descuentoGlobal,
                'monto_descuento'   => $montoDescuento,
                'subtotal'          => $subtotalConDesc,
                'impuesto_total'    => $impuestoTotal,
                'total'             => $subtotalConDesc + $impuestoTotal,
                'monto_pagado'      => 0,
                'estado'            => 'pendiente',
                'notas'             => $request->notas,
            ]);

            $pedido->update(['estado_factura' => 'completado']);

            return $factura;
        });

        event(new FacturaEmitida($factura));

        return redirect()->route('facturas.show', $factura)
            ->with('success', 'Factura generada correctamente.');
    }

    public function show(Factura $factura)
    {
        $factura->load(['pedido.cliente', 'pedido.detalles.producto', 'pagos.metodoPago', 'notasCredito']);
        $config = Configuracion::obtener();
        $metodosPago = \App\Models\MetodoPago::where('activo', true)->orderBy('nombre')->get();

        return view('facturas.detalle', compact('factura', 'config', 'metodosPago'));
    }

    private function esEditable(Factura $factura): bool
    {
        return $factura->estado === 'pendiente' && (float) $factura->monto_pagado <= 0;
    }

    public function edit(Factura $factura)
    {
        if (!$this->esEditable($factura)) {
            return redirect()->route('facturas.show', $factura)
                ->with('error', 'No se puede editar una factura con pagos registrados o ya anulada.');
        }

        $factura->load('pedido.detalles.producto');
        $productos = \App\Models\Producto::activos()->orderBy('nombre')->get();

        return view('facturas.editar', compact('factura', 'productos'));
    }

    public function update(Request $request, Factura $factura)
    {
        if (!$this->esEditable($factura)) {
            return redirect()->route('facturas.show', $factura)
                ->with('error', 'No se puede editar una factura con pagos registrados o ya anulada.');
        }

        $request->validate([
            'condicion_venta' => 'required|in:contado,credito',
            'productos'       => 'required|array|min:1',
            'productos.*.producto_id'     => 'required|exists:productos,id',
            'productos.*.cantidad'        => 'required|numeric|min:0.01',
            'productos.*.precio_unitario' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request, $factura) {
            $pedido = $factura->pedido;

            // Reemplazar los items del pedido asociado (la factura muestra los productos en vivo desde el pedido)
            $pedido->detalles()->delete();
            $subtotal = 0;
            foreach ($request->productos as $item) {
                $producto = \App\Models\Producto::find($item['producto_id']);
                $sub = $item['cantidad'] * $item['precio_unitario'];
                $subtotal += $sub;
                $pedido->detalles()->create([
                    'producto_id'     => $item['producto_id'],
                    'cantidad'        => $item['cantidad'],
                    'precio_unitario' => $item['precio_unitario'],
                    'descuento'       => $item['descuento'] ?? 0,
                    'impuesto'        => $producto->impuesto?->porcentaje ?? 0,
                    'subtotal'        => $sub,
                ]);
            }

            $impuestoTotal = collect($request->productos)->sum(function ($item) {
                $producto = \App\Models\Producto::find($item['producto_id']);
                $sub = $item['cantidad'] * $item['precio_unitario'];
                return $sub * (($producto->impuesto?->porcentaje ?? 0) / 100);
            });

            $pedido->update(['total' => $subtotal]);

            $factura->update([
                'condicion_venta'          => $request->condicion_venta,
                'tipo_documento_cliente'   => $request->tipo_documento_cliente,
                'numero_documento_cliente' => $request->numero_documento_cliente,
                'subtotal'                 => $subtotal,
                'impuesto_total'           => $impuestoTotal,
                'total'                    => $subtotal + $impuestoTotal,
                'notas'                    => $request->notas,
            ]);
        });

        return redirect()->route('facturas.show', $factura)->with('success', 'Factura actualizada correctamente.');
    }

    public function pdf(Factura $factura)
    {
        $factura->load(['pedido.cliente', 'pedido.detalles.producto']);
        $config = Configuracion::obtener();

        $pdf = Pdf::loadView('facturas.pdf', compact('factura', 'config'))->setPaper('a4');

        return $pdf->stream("factura-{$factura->numero_documento}.pdf");
    }

    public function destroy(Factura $factura)
    {
        if (\App\Support\Cierre::estaBloqueada($factura->fecha_factura)) {
            return redirect()->route('facturas.index')->with('error', \App\Support\Cierre::mensajeBloqueo());
        }

        if ($factura->estado !== 'pendiente' || $factura->monto_pagado > 0) {
            return redirect()->route('facturas.index')
                ->with('error', 'No se puede eliminar una factura con pagos registrados. Generá una Nota de Crédito en su lugar.');
        }

        if ($factura->notasCredito()->exists()) {
            return redirect()->route('facturas.index')
                ->with('error', 'No se puede eliminar una factura con notas de crédito asociadas.');
        }

        $factura->pedido?->update(['estado_factura' => 'pendiente']);
        $factura->delete();

        return redirect()->route('facturas.index')->with('success', 'Factura eliminada.');
    }
}
