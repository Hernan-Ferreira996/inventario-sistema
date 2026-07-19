<?php

namespace App\Http\Controllers;

use App\Models\Ciudad;
use Illuminate\Http\Request;

class CiudadController extends Controller
{
    public function index()
    {
        $ciudades = Ciudad::orderBy('departamento')->orderBy('nombre')->paginate(30);
        return view('ciudades.lista', compact('ciudades'));
    }

    public function create()
    {
        return view('ciudades.crear');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre'       => 'required|string|max:100',
            'departamento' => 'nullable|string|max:100',
            'pais'         => 'required|string|max:60',
        ]);

        Ciudad::create($validated);

        return redirect()->route('ciudades.index')->with('exito', 'Ciudad creada.');
    }

    public function edit(Ciudad $ciudad)
    {
        return view('ciudades.editar', compact('ciudad'));
    }

    public function update(Request $request, Ciudad $ciudad)
    {
        $validated = $request->validate([
            'nombre'       => 'required|string|max:100',
            'departamento' => 'nullable|string|max:100',
            'pais'         => 'required|string|max:60',
            'activo'       => 'boolean',
        ]);
        $validated['activo'] = $request->boolean('activo');

        $ciudad->update($validated);

        return redirect()->route('ciudades.index')->with('exito', 'Ciudad actualizada.');
    }

    public function destroy(Ciudad $ciudad)
    {
        if ($ciudad->clientes()->exists() || $ciudad->proveedores()->exists()) {
            return redirect()->route('ciudades.index')->with('error', 'No se puede eliminar: hay clientes o proveedores con esta ciudad. Podés desactivarla.');
        }
        $ciudad->delete();

        return redirect()->route('ciudades.index')->with('exito', 'Ciudad eliminada.');
    }
}
