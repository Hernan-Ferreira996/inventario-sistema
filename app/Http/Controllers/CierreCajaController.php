<?php

namespace App\Http\Controllers;

use App\Models\Caja;
use App\Models\CierreCaja;
use App\Models\Pago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CierreCajaController extends Controller
{
    public function index()
    {
        $cierres = CierreCaja::with(['caja', 'usuario'])->latest('fecha')->paginate(20);
        $cajas = Caja::where('activo', true)->orderBy('nombre')->get();

        return view('cierres-caja.index', compact('cierres', 'cajas'));
    }

    public function create(Request $request)
    {
        $cajas = Caja::where('activo', true)->orderBy('nombre')->get();

        $preview = null;
        if ($request->filled('caja_id')) {
            $caja = Caja::findOrFail($request->caja_id);
            [$saldoInicial, $totalCobrado] = $this->calcular($caja);
            $preview = [
                'caja' => $caja,
                'saldo_inicial' => $saldoInicial,
                'total_cobrado' => $totalCobrado,
                'saldo_final' => $saldoInicial + $totalCobrado,
            ];
        }

        return view('cierres-caja.crear', compact('cajas', 'preview'));
    }

    private function calcular(Caja $caja): array
    {
        $ultimoCierre = CierreCaja::where('caja_id', $caja->id)->latest('fecha')->first();
        $saldoInicial = $ultimoCierre ? (float) $ultimoCierre->saldo_final : 0.0;

        $query = Pago::where('caja_id', $caja->id);
        if ($ultimoCierre) {
            $query->where('fecha_pago', '>', $ultimoCierre->fecha);
        }
        $totalCobrado = (float) $query->sum('monto');

        return [$saldoInicial, $totalCobrado];
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'caja_id'       => 'required|exists:cajas,id',
            'fecha'         => 'required|date',
            'observaciones' => 'nullable|string|max:500',
        ]);

        $caja = Caja::findOrFail($validated['caja_id']);
        [$saldoInicial, $totalCobrado] = $this->calcular($caja);

        CierreCaja::create([
            'caja_id'       => $caja->id,
            'usuario_id'    => Auth::id(),
            'fecha'         => $validated['fecha'],
            'saldo_inicial' => $saldoInicial,
            'total_cobrado' => $totalCobrado,
            'saldo_final'   => $saldoInicial + $totalCobrado,
            'observaciones' => $validated['observaciones'] ?? null,
        ]);

        return redirect()->route('cierres-caja.index')->with('success', 'Caja cerrada correctamente.');
    }
}
