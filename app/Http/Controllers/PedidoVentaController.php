<?php

namespace App\Http\Controllers;

use App\Models\PedidoVenta;
use App\Models\DetallePedidoVenta;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\MovimientoStock;
use App\Models\Ubicacion;
use App\Models\TerminoPago;
use App\Support\Numeracion;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PedidoVentaController extends Controller
{
    /**
     * Adjunta a cada producto su stock disponible (físico total menos lo
     * comprometido en otros pedidos activos aún no despachados), calculado
     * en bloque para evitar N+1, para que el formulario de pedido pueda
     * advertir si se está vendiendo por encima de lo disponible.
     *
     * $excluirPedidoId excluye del cálculo de comprometido las líneas del
     * propio pedido que se está editando: ya están reservadas por él, no
     * deben restarse de su propia disponibilidad.
     */
    private function conStockDisponible($productos, ?int $excluirPedidoId = null)
    {
        $ids = $productos->pluck('id');

        $totales = MovimientoStock::whereIn('producto_id', $ids)
            ->selectRaw('producto_id, SUM(cantidad) as total')
            ->groupBy('producto_id')
            ->pluck('total', 'producto_id');

        $comprometidos = DetallePedidoVenta::whereIn('producto_id', $ids)
            ->whereColumn('cantidad', '>', 'cantidad_enviada')
            ->whereHas('pedido', function ($q) use ($excluirPedidoId) {
                $q->where('estado', 'activo');
                if ($excluirPedidoId) {
                    $q->where('id', '!=', $excluirPedidoId);
                }
            })
            ->selectRaw('producto_id, SUM(cantidad - cantidad_enviada) as total')
            ->groupBy('producto_id')
            ->pluck('total', 'producto_id');

        $productos->each(function ($p) use ($totales, $comprometidos) {
            $p->stock_disponible = (float) ($totales[$p->id] ?? 0) - (float) ($comprometidos[$p->id] ?? 0);
        });

        return $productos;
    }

    public function index(Request $request)
    {
        $query = PedidoVenta::with(['cliente', 'usuario']);

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($w) use ($q) {
                $w->where('numero_referencia', 'like', "%{$q}%")
                  ->orWhereHas('cliente', fn($c) => $c->where('nombre', 'like', "%{$q}%"));
            });
        }
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        if ($request->filled('estado_factura')) {
            $query->where('estado_factura', $request->estado_factura);
        }
        if ($request->filled('desde')) {
            $query->whereDate('fecha_pedido', '>=', $request->desde);
        }
        if ($request->filled('hasta')) {
            $query->whereDate('fecha_pedido', '<=', $request->hasta);
        }

        $pedidos = $query->latest('fecha_pedido')->paginate(20)->withQueryString();

        return view('pedidos.lista', compact('pedidos'));
    }

    public function create()
    {
        $clientes     = Cliente::where('activo', true)->orderBy('nombre')->get();
        $productos    = $this->conStockDisponible(Producto::activos()->orderBy('nombre')->get());
        $ubicaciones  = Ubicacion::where('activo', true)->visiblesPara(auth()->user())->orderBy('nombre')->get();
        $terminosPago = TerminoPago::orderBy('nombre')->get();

        $proximoNumero = Numeracion::previsualizar('pedidos_venta', null, 'PV-');

        return view('pedidos.crear', compact('clientes', 'productos', 'ubicaciones', 'terminosPago', 'proximoNumero'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id'       => 'required|exists:clientes,id',
            'ubicacion_id'     => 'nullable|exists:ubicaciones,id',
            'termino_pago_id'  => 'nullable|exists:terminos_pago,id',
            'fecha_pedido'     => 'required|date',
            'fecha_entrega'    => 'nullable|date|after_or_equal:fecha_pedido',
            'comentarios'      => 'nullable|string',
            'productos'        => 'required|array|min:1',
            'productos.*.producto_id'    => 'required|exists:productos,id',
            'productos.*.cantidad'       => 'required|numeric|min:0.01',
            'productos.*.precio_unitario' => 'required|numeric|min:0',
        ]);

        $total = collect($request->productos)->sum(fn($d) =>
            $d['cantidad'] * $d['precio_unitario'] * (1 - ($d['descuento'] ?? 0) / 100)
        );

        $pedido = PedidoVenta::create([
            'cliente_id'         => $request->cliente_id,
            'usuario_id'         => auth()->id(),
            'ubicacion_id'       => $request->ubicacion_id,
            'termino_pago_id'    => $request->termino_pago_id,
            'numero_referencia'  => Numeracion::siguiente('pedidos_venta', null, 'PV-'),
            'referencia_cliente' => $request->referencia_cliente,
            'comentarios'        => $request->comentarios,
            'fecha_pedido'       => $request->fecha_pedido,
            'fecha_entrega'      => $request->fecha_entrega,
            'direccion_entrega'  => $request->direccion_entrega,
            'total'              => $total,
            'estado'             => 'activo',
            'estado_factura'     => 'pendiente',
        ]);

        foreach ($request->productos as $detalle) {
            $producto = Producto::find($detalle['producto_id']);
            $subtotal = $detalle['cantidad'] * $detalle['precio_unitario'];

            $pedido->detalles()->create([
                'producto_id'     => $detalle['producto_id'],
                'cantidad'        => $detalle['cantidad'],
                'precio_unitario' => $detalle['precio_unitario'],
                'descuento'       => $detalle['descuento'] ?? 0,
                'impuesto'        => $producto->impuesto?->porcentaje ?? 0,
                'subtotal'        => $subtotal,
            ]);
        }

        return redirect()->route('pedidos.show', $pedido)
            ->with('exito', 'Pedido de venta creado exitosamente.');
    }

    public function show(PedidoVenta $pedido)
    {
        $pedido->load(['cliente', 'usuario', 'detalles.producto', 'facturas', 'pagos', 'envios']);

        return view('pedidos.detalle', compact('pedido'));
    }

    public function edit(PedidoVenta $pedido)
    {
        $pedido->load(['detalles.producto']);
        $clientes     = Cliente::where('activo', true)->orderBy('nombre')->get();
        $productos    = $this->conStockDisponible(Producto::activos()->orderBy('nombre')->get(), $pedido->id);
        $ubicaciones  = Ubicacion::where('activo', true)->visiblesPara(auth()->user())->orderBy('nombre')->get();
        $terminosPago = TerminoPago::orderBy('nombre')->get();

        return view('pedidos.editar', compact('pedido', 'clientes', 'productos', 'ubicaciones', 'terminosPago'));
    }

    public function update(Request $request, PedidoVenta $pedido)
    {
        $request->validate([
            'cliente_id'       => 'required|exists:clientes,id',
            'ubicacion_id'     => 'nullable|exists:ubicaciones,id',
            'termino_pago_id'  => 'nullable|exists:terminos_pago,id',
            'fecha_pedido'     => 'required|date',
            'fecha_entrega'    => 'nullable|date|after_or_equal:fecha_pedido',
            'comentarios'      => 'nullable|string',
            'productos'        => 'required|array|min:1',
            'productos.*.producto_id'     => 'required|exists:productos,id',
            'productos.*.cantidad'        => 'required|numeric|min:0.01',
            'productos.*.precio_unitario' => 'required|numeric|min:0',
        ]);

        $total = collect($request->productos)->sum(fn($d) =>
            $d['cantidad'] * $d['precio_unitario'] * (1 - ($d['descuento'] ?? 0) / 100)
        );

        $pedido->update([
            'cliente_id'         => $request->cliente_id,
            'ubicacion_id'       => $request->ubicacion_id,
            'termino_pago_id'    => $request->termino_pago_id,
            'referencia_cliente' => $request->referencia_cliente,
            'comentarios'        => $request->comentarios,
            'fecha_pedido'       => $request->fecha_pedido,
            'fecha_entrega'      => $request->fecha_entrega,
            'direccion_entrega'  => $request->direccion_entrega,
            'total'              => $total,
        ]);

        $pedido->detalles()->delete();

        foreach ($request->productos as $detalle) {
            $producto = Producto::find($detalle['producto_id']);
            $subtotal = $detalle['cantidad'] * $detalle['precio_unitario'];

            $pedido->detalles()->create([
                'producto_id'     => $detalle['producto_id'],
                'cantidad'        => $detalle['cantidad'],
                'precio_unitario' => $detalle['precio_unitario'],
                'descuento'       => $detalle['descuento'] ?? 0,
                'impuesto'        => $producto->impuesto?->porcentaje ?? 0,
                'subtotal'        => $subtotal,
            ]);
        }

        return redirect()->route('pedidos.show', $pedido)
            ->with('exito', 'Pedido de venta actualizado exitosamente.');
    }

    public function destroy(PedidoVenta $pedido)
    {
        if (\App\Support\Cierre::estaBloqueada($pedido->fecha_pedido)) {
            return redirect()->route('pedidos.index')->with('error', \App\Support\Cierre::mensajeBloqueo());
        }

        if ($pedido->facturas()->exists()) {
            return redirect()->route('pedidos.index')
                ->with('error', 'No se puede eliminar un pedido que ya tiene factura generada. Anulá la factura primero mediante una Nota de Crédito.');
        }

        $pedido->detalles()->delete();
        $pedido->delete();

        return redirect()->route('pedidos.index')
            ->with('exito', 'Pedido eliminado exitosamente.');
    }

    public function buscarProducto(Request $request)
    {
        $productos = Producto::activos()
            ->where(function ($q) use ($request) {
                $q->where('nombre', 'like', "%{$request->q}%")
                  ->orWhere('codigo', 'like', "%{$request->q}%");
            })
            ->with('impuesto')
            ->take(10)
            ->get(['id', 'codigo', 'nombre', 'precio_venta_minorista', 'precio_venta_mayorista', 'impuesto_id']);

        return response()->json($productos);
    }
}
