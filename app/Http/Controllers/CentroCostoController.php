<?php

namespace App\Http\Controllers;

use App\Models\CentroCosto;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CentroCostoController extends Controller
{
    public function index()
    {
        $centrosCosto = CentroCosto::orderBy('codigo')->paginate(20);
        $centrosCosto->each(fn($c) => $c->pedidos_compra_count = $c->pedidosCompra()->count());
        return view('centros-costo.lista', compact('centrosCosto'));
    }

    public function create()
    {
        return view('centros-costo.crear');
    }

    public function store(Request $request)
    {
        $empresaId = auth()->user()->empresa_id;
        $validated = $request->validate([
            'codigo'      => ['required', 'string', 'max:20', Rule::unique('centros_costo')->where('empresa_id', $empresaId)],
            'nombre'      => 'required|string|max:100',
            'descripcion' => 'nullable|string',
        ]);

        CentroCosto::create($validated);

        return redirect()->route('centros-costo.index')->with('exito', 'Centro de costo creado.');
    }

    public function edit(CentroCosto $centroCosto)
    {
        return view('centros-costo.editar', compact('centroCosto'));
    }

    public function update(Request $request, CentroCosto $centroCosto)
    {
        $validated = $request->validate([
            'codigo'      => ['required', 'string', 'max:20', Rule::unique('centros_costo')->where('empresa_id', $centroCosto->empresa_id)->ignore($centroCosto->id)],
            'nombre'      => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'activo'      => 'boolean',
        ]);
        $validated['activo'] = $request->boolean('activo');

        $centroCosto->update($validated);

        return redirect()->route('centros-costo.index')->with('exito', 'Centro de costo actualizado.');
    }

    public function destroy(CentroCosto $centroCosto)
    {
        if ($centroCosto->pedidosCompra()->exists()) {
            return redirect()->route('centros-costo.index')->with('error', 'No se puede eliminar: tiene compras asociadas. Podés desactivarlo.');
        }
        $centroCosto->delete();

        return redirect()->route('centros-costo.index')->with('exito', 'Centro de costo eliminado.');
    }
}
