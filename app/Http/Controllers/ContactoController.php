<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Contacto;
use App\Models\Proveedor;
use Illuminate\Http\Request;

class ContactoController extends Controller
{
    private function reglas(): array
    {
        return [
            'nombre'       => 'required|string|max:150',
            'cargo'        => 'nullable|string|max:100',
            'telefono'     => 'nullable|string|max:30',
            'email'        => 'nullable|email|max:150',
            'cumpleanos'   => 'nullable|date',
            'es_principal' => 'boolean',
        ];
    }

    public function storeCliente(Request $request, Cliente $cliente)
    {
        $data = $request->validate($this->reglas());
        $data['empresa_id'] = $cliente->empresa_id;
        $cliente->contactos()->create($data);
        return back()->with('success', 'Contacto agregado.');
    }

    public function storeProveedor(Request $request, Proveedor $proveedor)
    {
        $data = $request->validate($this->reglas());
        $data['empresa_id'] = $proveedor->empresa_id;
        $proveedor->contactos()->create($data);
        return back()->with('success', 'Contacto agregado.');
    }

    public function destroy(Contacto $contacto)
    {
        abort_unless($contacto->empresa_id === auth()->user()->empresa_id, 403);
        $contacto->delete();
        return back()->with('success', 'Contacto eliminado.');
    }
}
