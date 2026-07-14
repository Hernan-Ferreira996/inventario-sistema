<?php

namespace App\Http\Controllers;

use App\Models\AsientoContable;
use App\Models\CuentaContable;
use Illuminate\Http\Request;

class AsientoContableController extends Controller
{
    public function index(Request $request)
    {
        $query = AsientoContable::withCount('movimientos');
        if ($request->filled('desde')) {
            $query->whereDate('fecha', '>=', $request->desde);
        }
        if ($request->filled('hasta')) {
            $query->whereDate('fecha', '<=', $request->hasta);
        }
        $asientos = $query->latest('fecha')->latest('id')->paginate(20)->withQueryString();
        return view('contabilidad.asientos.lista', compact('asientos'));
    }

    public function create()
    {
        $cuentas = CuentaContable::where('imputable', true)->where('activo', true)->orderBy('codigo')->get();
        return view('contabilidad.asientos.crear', compact('cuentas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'concepto'            => 'required|string|max:255',
            'lineas'              => 'required|array|min:2',
            'lineas.*.cuenta_id'  => 'required|exists:cuentas_contables,id',
            'lineas.*.debe'       => 'nullable|numeric|min:0',
            'lineas.*.haber'      => 'nullable|numeric|min:0',
        ]);

        $lineas = collect($request->lineas)->map(function ($l) {
            $cuenta = CuentaContable::find($l['cuenta_id']);
            return [
                'cuenta_codigo' => $cuenta->codigo,
                'debe'  => $l['debe'] ?? 0,
                'haber' => $l['haber'] ?? 0,
                'descripcion' => $l['descripcion'] ?? null,
            ];
        })->all();

        try {
            $asiento = AsientoContable::crear($request->concepto, 'manual', $lineas);
        } catch (\RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('contabilidad.asientos.show', $asiento)->with('success', 'Asiento registrado correctamente.');
    }

    public function show(AsientoContable $asiento)
    {
        $asiento->load('movimientos.cuenta', 'usuario');
        return view('contabilidad.asientos.detalle', compact('asiento'));
    }
}
