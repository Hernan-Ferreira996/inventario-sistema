<?php

namespace App\Http\Controllers;

use App\Models\Caja;
use Illuminate\Http\Request;

class CajaController extends Controller
{
    public function index()
    {
        $cajas = Caja::orderBy('nombre')->paginate(20);
        $cajas->each(fn($c) => $c->pagos_count = $c->pagos()->count());

        return view('cajas.lista', compact('cajas'));
    }

    public function create()
    {
        return view('cajas.crear');
    }

    public function store(Request $request)
    {
        $validated = $request->validate(['nombre' => 'required|string|max:100']);
        Caja::create($validated);

        return redirect()->route('cajas.index')->with('exito', 'Caja creada.');
    }

    public function edit(Caja $caja)
    {
        return view('cajas.editar', compact('caja'));
    }

    public function update(Request $request, Caja $caja)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'activo' => 'boolean',
        ]);
        $validated['activo'] = $request->boolean('activo');

        $caja->update($validated);

        return redirect()->route('cajas.index')->with('exito', 'Caja actualizada.');
    }

    public function destroy(Caja $caja)
    {
        if ($caja->pagos()->exists()) {
            return redirect()->route('cajas.index')->with('error', 'No se puede eliminar: tiene pagos asociados. Podés desactivarla.');
        }
        $caja->delete();

        return redirect()->route('cajas.index')->with('exito', 'Caja eliminada.');
    }
}
