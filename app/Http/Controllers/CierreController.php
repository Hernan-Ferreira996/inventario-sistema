<?php

namespace App\Http\Controllers;

use App\Models\CierrePeriodo;
use App\Support\Cierre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CierreController extends Controller
{
    public function index()
    {
        $cierres = CierrePeriodo::with('usuario')->latest('fecha_cierre')->paginate(20);
        $fechaVigente = Cierre::fechaVigente();

        return view('cierres.index', compact('cierres', 'fechaVigente'));
    }

    public function store(Request $request)
    {
        $vigente = Cierre::fechaVigente();

        $reglasFecha = ['required', 'date', 'before_or_equal:today'];
        if ($vigente) {
            $reglasFecha[] = 'after:' . $vigente->toDateString();
        }

        $validated = $request->validate([
            'fecha_cierre'  => $reglasFecha,
            'observaciones' => 'nullable|string|max:500',
        ], [
            'fecha_cierre.after' => 'La fecha de cierre debe ser posterior al último cierre registrado (' . ($vigente?->format('d/m/Y')) . ').',
        ]);

        CierrePeriodo::create([
            'empresa_id'    => auth()->user()->empresa_id,
            'usuario_id'    => Auth::id(),
            'fecha_cierre'  => $validated['fecha_cierre'],
            'observaciones' => $validated['observaciones'] ?? null,
        ]);

        return redirect()->route('cierres.index')->with('success', 'Período cerrado correctamente. Los documentos con fecha hasta ' . $validated['fecha_cierre'] . ' ya no se pueden eliminar ni anular.');
    }
}
