<?php

namespace App\Http\Controllers;

use App\Models\CatalogoValor;
use App\Models\CuentaContable;
use Illuminate\Http\Request;

class CuentaContableController extends Controller
{
    public function index()
    {
        $cuentas = CuentaContable::with('padre')->orderBy('codigo')->get();
        return view('contabilidad.cuentas.lista', compact('cuentas'));
    }

    public function create()
    {
        $cuentasPadre = CuentaContable::where('imputable', false)->orderBy('codigo')->get();
        return view('contabilidad.cuentas.crear', compact('cuentasPadre'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'codigo'          => 'required|string|max:20|unique:cuentas_contables,codigo',
            'nombre'          => 'required|string|max:150',
            'tipo'            => 'required|in:' . implode(',', CatalogoValor::codigos('cuentas_contables.tipo')),
            'naturaleza'      => 'required|in:deudora,acreedora',
            'cuenta_padre_id' => 'nullable|exists:cuentas_contables,id',
        ]);
        $data['imputable'] = $request->boolean('imputable', true);

        CuentaContable::create($data);

        return redirect()->route('contabilidad.cuentas.index')->with('success', 'Cuenta creada.');
    }

    public function edit(CuentaContable $cuenta)
    {
        $cuentasPadre = CuentaContable::where('imputable', false)->where('id', '!=', $cuenta->id)->orderBy('codigo')->get();
        return view('contabilidad.cuentas.editar', compact('cuenta', 'cuentasPadre'));
    }

    public function update(Request $request, CuentaContable $cuenta)
    {
        $data = $request->validate([
            'nombre'          => 'required|string|max:150',
            'tipo'            => 'required|in:' . implode(',', CatalogoValor::codigos('cuentas_contables.tipo')),
            'naturaleza'      => 'required|in:deudora,acreedora',
            'cuenta_padre_id' => 'nullable|exists:cuentas_contables,id',
        ]);
        $data['activo'] = $request->boolean('activo');

        $cuenta->update($data);

        return redirect()->route('contabilidad.cuentas.index')->with('success', 'Cuenta actualizada.');
    }

    public function destroy(CuentaContable $cuenta)
    {
        if ($cuenta->movimientos()->exists() || $cuenta->hijas()->exists()) {
            return back()->with('error', 'No se puede eliminar: la cuenta tiene movimientos o subcuentas asociadas.');
        }
        $cuenta->delete();
        return redirect()->route('contabilidad.cuentas.index')->with('success', 'Cuenta eliminada.');
    }
}
