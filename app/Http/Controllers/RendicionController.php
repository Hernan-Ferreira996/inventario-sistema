<?php

namespace App\Http\Controllers;

use App\Models\Caja;
use App\Models\Pago;
use App\Models\Rendicion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RendicionController extends Controller
{
    public function index()
    {
        $rendiciones = Rendicion::with(['caja', 'cobrador', 'usuario'])->latest('fecha')->paginate(20);

        return view('rendiciones.lista', compact('rendiciones'));
    }

    public function create()
    {
        $cajas = Caja::where('activo', true)->orderBy('nombre')->get();
        $cobradores = User::where('empresa_id', auth()->user()->empresa_id)->orderBy('name')->get();

        $pagosPendientes = collect();
        if (request()->filled('caja_id') && request()->filled('cobrador_id')) {
            $pagosPendientes = Pago::with('factura.pedido.cliente')
                ->where('caja_id', request('caja_id'))
                ->where('cobrador_id', request('cobrador_id'))
                ->whereNull('rendicion_id')
                ->orderBy('fecha_pago')
                ->get();
        }

        return view('rendiciones.crear', compact('cajas', 'cobradores', 'pagosPendientes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'caja_id'       => 'required|exists:cajas,id',
            'cobrador_id'   => 'required|exists:users,id',
            'fecha'         => 'required|date',
            'observaciones' => 'nullable|string|max:500',
        ]);

        $pagosPendientes = Pago::where('caja_id', $validated['caja_id'])
            ->where('cobrador_id', $validated['cobrador_id'])
            ->whereNull('rendicion_id')
            ->get();

        if ($pagosPendientes->isEmpty()) {
            return back()->withInput()->with('error', 'No hay pagos pendientes de rendir para ese cobrador y esa caja.');
        }

        $rendicion = DB::transaction(function () use ($validated, $pagosPendientes) {
            $rendicion = Rendicion::create([
                'caja_id'       => $validated['caja_id'],
                'cobrador_id'   => $validated['cobrador_id'],
                'usuario_id'    => Auth::id(),
                'fecha'         => $validated['fecha'],
                'monto_total'   => $pagosPendientes->sum('monto'),
                'observaciones' => $validated['observaciones'] ?? null,
            ]);

            Pago::whereIn('id', $pagosPendientes->pluck('id'))->update(['rendicion_id' => $rendicion->id]);

            return $rendicion;
        });

        return redirect()->route('rendiciones.show', $rendicion)->with('success', 'Rendición registrada correctamente.');
    }

    public function show(Rendicion $rendicion)
    {
        $rendicion->load(['caja', 'cobrador', 'usuario', 'pagos.factura.pedido.cliente']);

        return view('rendiciones.detalle', compact('rendicion'));
    }
}
