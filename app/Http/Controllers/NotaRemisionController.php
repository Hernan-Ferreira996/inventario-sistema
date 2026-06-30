<?php
namespace App\Http\Controllers;

use App\Models\NotaRemision;
use App\Models\PedidoVenta;
use App\Models\Ubicacion;
use App\Models\MovimientoStock;
use App\Support\Configuracion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class NotaRemisionController extends Controller
{
    public function index()
    {
        $notas = NotaRemision::with('pedido.cliente')->latest()->paginate(20);
        return view('notas-remision.lista', compact('notas'));
    }

    public function create(Request $request)
    {
        $pedido = PedidoVenta::with(['cliente', 'detalles.producto'])->findOrFail($request->pedido);
        $config = Configuracion::obtener();
        $ubicaciones = Ubicacion::where('activo', true)->orderBy('nombre')->get();
        $proximoNumero = $this->generarNumero();

        return view('notas-remision.crear', compact('pedido', 'config', 'ubicaciones', 'proximoNumero'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pedido_id'           => 'required|exists:pedidos_venta,id',
            'ubicacion_origen_id' => 'required|exists:ubicaciones,id',
            'motivo'              => 'required|in:venta,consignacion,traslado,devolucion,otro',
            'direccion_destino'   => 'nullable|string|max:255',
            'transportista'       => 'nullable|string|max:150',
            'vehiculo_placa'      => 'nullable|string|max:20',
            'productos'           => 'required|array|min:1',
            'productos.*.producto_id' => 'required|exists:productos,id',
            'productos.*.cantidad'    => 'required|numeric|min:0.01',
        ]);

        $config = Configuracion::obtener();

        $nota = DB::transaction(function () use ($request, $config) {
            $nota = NotaRemision::create([
                'pedido_id'           => $request->pedido_id,
                'usuario_id'          => Auth::id(),
                'ubicacion_origen_id' => $request->ubicacion_origen_id,
                'numero_documento'    => $this->generarNumero(),
                'timbrado'            => $config['fact_timbrado'],
                'establecimiento'     => $config['fact_establecimiento'],
                'punto_expedicion'    => $config['fact_punto_expedicion'],
                'modo'                => $config['fact_modo'],
                'fecha_emision'       => now()->toDateString(),
                'motivo'              => $request->motivo,
                'direccion_destino'   => $request->direccion_destino,
                'transportista'       => $request->transportista,
                'vehiculo_placa'      => $request->vehiculo_placa,
                'observaciones'       => $request->observaciones,
            ]);

            foreach ($request->productos as $p) {
                $nota->detalles()->create([
                    'producto_id' => $p['producto_id'],
                    'cantidad'    => $p['cantidad'],
                ]);

                // La Nota de Remisión documenta la salida física de mercadería:
                // se descuenta el stock del almacén de origen al momento de emitirla.
                MovimientoStock::create([
                    'producto_id'      => $p['producto_id'],
                    'ubicacion_id'     => $request->ubicacion_origen_id,
                    'usuario_id'       => Auth::id(),
                    'cantidad'         => -abs($p['cantidad']),
                    'tipo'             => 'salida',
                    'referencia'       => $nota->numero_completo,
                    'notas'            => 'Salida por Nota de Remisión',
                    'fecha_movimiento' => now(),
                ]);
            }

            return $nota;
        });

        return redirect()->route('notas-remision.show', $nota)
            ->with('success', 'Nota de remisión generada correctamente.');
    }

    public function show(NotaRemision $notaRemision)
    {
        $notaRemision->load(['pedido.cliente', 'detalles.producto', 'usuario', 'ubicacionOrigen']);
        $config = Configuracion::obtener();

        return view('notas-remision.detalle', compact('notaRemision', 'config'));
    }

    public function pdf(NotaRemision $notaRemision)
    {
        $notaRemision->load(['pedido.cliente', 'detalles.producto', 'ubicacionOrigen']);
        $config = Configuracion::obtener();

        $pdf = Pdf::loadView('notas-remision.pdf', compact('notaRemision', 'config'))->setPaper('a4');

        return $pdf->stream("nota-remision-{$notaRemision->numero_completo}.pdf");
    }

    public function destroy(NotaRemision $notaRemision)
    {
        $notaRemision->delete();
        return redirect()->route('notas-remision.index')->with('success', 'Nota de remisión eliminada.');
    }

    private function generarNumero(): string
    {
        $ultimo = NotaRemision::max('id') ?? 0;
        return str_pad($ultimo + 1, 7, '0', STR_PAD_LEFT);
    }
}
