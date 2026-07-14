<?php

namespace App\Http\Controllers;

use App\Models\CatalogoValor;
use App\Models\Cliente;
use App\Models\Interaccion;
use App\Models\Proveedor;
use Illuminate\Http\Request;

class InteraccionController extends Controller
{
    private function reglas(): array
    {
        return [
            'tipo'        => 'required|in:' . implode(',', CatalogoValor::codigos('interacciones.tipo')),
            'fecha'       => 'required|date',
            'descripcion' => 'required|string',
        ];
    }

    public function storeCliente(Request $request, Cliente $cliente)
    {
        $data = $request->validate($this->reglas());
        $data['empresa_id'] = $cliente->empresa_id;
        $data['usuario_id'] = auth()->id();
        $cliente->interacciones()->create($data);
        return back()->with('success', 'Interacción registrada.');
    }

    public function storeProveedor(Request $request, Proveedor $proveedor)
    {
        $data = $request->validate($this->reglas());
        $data['empresa_id'] = $proveedor->empresa_id;
        $data['usuario_id'] = auth()->id();
        $proveedor->interacciones()->create($data);
        return back()->with('success', 'Interacción registrada.');
    }

    public function destroy(Interaccion $interaccion)
    {
        abort_unless($interaccion->empresa_id === auth()->user()->empresa_id, 403);
        $interaccion->delete();
        return back()->with('success', 'Interacción eliminada.');
    }
}
