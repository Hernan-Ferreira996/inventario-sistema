<?php
namespace App\Http\Controllers;

use App\Models\NotaCredito;
use App\Models\Factura;
use App\Models\MovimientoStock;
use App\Models\Ubicacion;
use App\Events\NotaCreditoEmitida;
use App\Support\Configuracion;
use App\Support\Numeracion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class NotaCreditoController extends Controller
{
    public function index()
    {
        $notas = NotaCredito::with('factura.pedido.cliente')->latest()->paginate(20);
        return view('notas-credito.lista', compact('notas'));
    }

    public function create(Request $request)
    {
        $factura = Factura::with(['pedido.cliente', 'pedido.detalles.producto'])->findOrFail($request->factura);
        $config = Configuracion::obtener();
        $proximoNumero = Numeracion::previsualizar('notas_credito', $factura->sucursal_id);
        $ubicaciones = Ubicacion::where('activo', true)->visiblesPara(Auth::user())->orderBy('nombre')->get();

        return view('notas-credito.crear', compact('factura', 'config', 'proximoNumero', 'ubicaciones'));
    }

    public function store(Request $request)
    {
        $restablecesStock = in_array($request->motivo, ['devolucion_total', 'devolucion_parcial', 'anulacion']);

        $request->validate([
            'factura_id'          => 'required|exists:facturas,id',
            'motivo'              => 'required|in:' . implode(',', \App\Models\CatalogoValor::codigos('notas_credito.motivo')),
            'descripcion_motivo'  => 'nullable|string',
            'ubicacion_id'        => $restablecesStock ? 'required|exists:ubicaciones,id' : 'nullable|exists:ubicaciones,id',
            'productos'           => 'required|array|min:1',
            'productos.*.producto_id'     => 'required|exists:productos,id',
            'productos.*.cantidad'        => 'required|numeric|min:0.01',
            'productos.*.precio_unitario' => 'required|numeric|min:0',
        ]);

        if ($restablecesStock) {
            Ubicacion::verificarAcceso(Auth::user(), (int) $request->ubicacion_id);
        }

        $factura = Factura::findOrFail($request->factura_id);
        $config = Configuracion::obtener();

        $subtotal = collect($request->productos)->sum(fn($p) => $p['cantidad'] * $p['precio_unitario']);

        $nota = DB::transaction(function () use ($request, $factura, $config, $subtotal, $restablecesStock) {
            $nota = NotaCredito::create([
                'factura_id'         => $factura->id,
                'usuario_id'         => Auth::id(),
                'numero_documento'   => Numeracion::siguiente('notas_credito', $factura->sucursal_id),
                'timbrado'           => $config['fact_timbrado'],
                'establecimiento'    => $config['fact_establecimiento'],
                'punto_expedicion'   => $config['fact_punto_expedicion'],
                'modo'               => $config['fact_modo'],
                'fecha_emision'      => now()->toDateString(),
                'motivo'             => $request->motivo,
                'descripcion_motivo' => $request->descripcion_motivo,
                'subtotal'           => $subtotal,
                'impuesto_total'     => 0,
                'total'              => $subtotal,
            ]);

            foreach ($request->productos as $p) {
                $nota->detalles()->create([
                    'producto_id'     => $p['producto_id'],
                    'cantidad'        => $p['cantidad'],
                    'precio_unitario' => $p['precio_unitario'],
                    'subtotal'        => $p['cantidad'] * $p['precio_unitario'],
                ]);

                // Devolución/anulación: la mercadería vuelve físicamente al almacén.
                if ($restablecesStock) {
                    MovimientoStock::create([
                        'producto_id'           => $p['producto_id'],
                        'ubicacion_id'          => $request->ubicacion_id,
                        'usuario_id'            => Auth::id(),
                        'cantidad'              => abs($p['cantidad']),
                        'tipo'                  => 'entrada',
                        'referencia'            => $nota->numero_completo,
                        'notas'                 => 'Devolución por Nota de Crédito',
                        'fecha_movimiento'      => now(),
                        'origen_documento_type' => $nota->getMorphClass(),
                        'origen_documento_id'   => $nota->id,
                    ]);
                }
            }

            // Si es devolución total o anulación, marcar la factura como anulada
            if ($request->motivo === 'anulacion' || $request->motivo === 'devolucion_total') {
                $factura->update(['estado' => 'anulada']);
            }

            return $nota;
        });

        event(new NotaCreditoEmitida($nota));

        return redirect()->route('notas-credito.show', $nota)
            ->with('success', 'Nota de crédito generada correctamente.');
    }

    public function show(NotaCredito $notaCredito)
    {
        $notaCredito->load(['factura.pedido.cliente', 'detalles.producto', 'usuario']);
        $config = Configuracion::obtener();

        return view('notas-credito.detalle', compact('notaCredito', 'config'));
    }

    public function pdf(NotaCredito $notaCredito)
    {
        $notaCredito->load(['factura.pedido.cliente', 'detalles.producto']);
        $config = Configuracion::obtener();

        $pdf = Pdf::loadView('notas-credito.pdf', compact('notaCredito', 'config'))->setPaper('a4');

        return $pdf->stream("nota-credito-{$notaCredito->numero_completo}.pdf");
    }

    public function destroy(NotaCredito $notaCredito)
    {
        if (\App\Support\Cierre::estaBloqueada($notaCredito->fecha_emision)) {
            return redirect()->route('notas-credito.index')->with('error', \App\Support\Cierre::mensajeBloqueo());
        }

        $notaCredito->delete();
        return redirect()->route('notas-credito.index')->with('success', 'Nota de crédito eliminada.');
    }
}
