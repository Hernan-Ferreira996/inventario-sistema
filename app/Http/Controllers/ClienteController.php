<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $query = Cliente::withCount('pedidos');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($w) use ($q) {
                $w->where('nombre', 'like', "%{$q}%")
                  ->orWhere('email', 'like', "%{$q}%")
                  ->orWhere('ruc_nit', 'like', "%{$q}%");
            });
        }
        if ($request->filled('tipo_precio')) {
            $query->where('tipo_precio', $request->tipo_precio);
        }

        $clientes = $query->orderBy('nombre')->paginate(20)->withQueryString();

        return view('clientes.lista', compact('clientes'));
    }

    public function create()
    {
        return view('clientes.crear');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre'      => 'required|string|max:100',
            'email'       => 'nullable|email|unique:clientes,email',
            'telefono'    => 'nullable|string|max:20',
            'direccion'   => 'nullable|string',
            'ruc_nit'     => 'nullable|string|max:30',
            'tipo_precio' => 'required|in:minorista,mayorista',
        ]);

        Cliente::create($validated);

        return redirect()->route('clientes.index')
            ->with('exito', 'Cliente creado exitosamente.');
    }

    public function show(Cliente $cliente)
    {
        $cliente->setRelation('pedidos', $cliente->pedidos()->orderBy('created_at', 'desc')->limit(10)->get());

        $resumen = [
            'total_pedidos'   => $cliente->pedidos()->count(),
            'total_comprado'  => $cliente->pedidos()->sum('total'),
            'saldo_pendiente' => $cliente->pedidos()->selectRaw('SUM(total - monto_pagado)')->value('SUM(total - monto_pagado)'),
        ];

        return view('clientes.detalle', compact('cliente', 'resumen'));
    }

    public function edit(Cliente $cliente)
    {
        return view('clientes.editar', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        $validated = $request->validate([
            'nombre'      => 'required|string|max:100',
            'email'       => ['nullable', 'email', Rule::unique('clientes')->ignore($cliente->id)],
            'telefono'    => 'nullable|string|max:20',
            'direccion'   => 'nullable|string',
            'ruc_nit'     => 'nullable|string|max:30',
            'tipo_precio' => 'required|in:minorista,mayorista',
            'activo'      => 'boolean',
        ]);

        $cliente->update($validated);

        return redirect()->route('clientes.show', $cliente)
            ->with('exito', 'Cliente actualizado exitosamente.');
    }

    public function destroy(Cliente $cliente)
    {
        $cliente->delete();

        return redirect()->route('clientes.index')
            ->with('exito', 'Cliente eliminado exitosamente.');
    }
}
