<?php
namespace App\Http\Controllers;

use App\Models\Factura;
use App\Models\Cliente;
use Illuminate\Http\Request;

class CobranzaController extends Controller
{
    public function index(Request $request)
    {
        $baseQuery = Factura::conSaldoPendiente();

        if ($request->filled('cliente_id')) {
            $baseQuery->whereHas('pedido', fn($p) => $p->where('cliente_id', $request->cliente_id));
        }

        $totales = [
            'saldo_total' => (clone $baseQuery)->get()->sum('saldo_pendiente'),
        ];

        $vencidasQuery = (clone $baseQuery)
            ->whereNotNull('fecha_vencimiento')->whereDate('fecha_vencimiento', '<', now());
        $totales['saldo_vencido'] = (clone $vencidasQuery)->get()->sum('saldo_pendiente');
        $totales['cantidad_vencidas'] = $vencidasQuery->count();

        $query = (clone $baseQuery)->with('pedido.cliente');
        if ($request->filled('solo_vencidas')) {
            $query->whereNotNull('fecha_vencimiento')->whereDate('fecha_vencimiento', '<', now());
        }

        $facturas = $query->orderBy('fecha_vencimiento')->paginate(20)->withQueryString();

        $clientes = Cliente::where('activo', true)->orderBy('nombre')->get();
        $clienteFiltrado = $request->filled('cliente_id') ? Cliente::find($request->cliente_id) : null;

        return view('cobranzas.index', compact('facturas', 'totales', 'clientes', 'clienteFiltrado'));
    }
}
