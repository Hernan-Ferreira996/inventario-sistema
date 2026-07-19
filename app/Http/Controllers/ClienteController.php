<?php

namespace App\Http\Controllers;

use App\Models\CampoPersonalizado;
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
        $campos = CampoPersonalizado::paraEntidad('cliente');
        return view('clientes.crear', compact('campos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre'         => 'required|string|max:100',
            'email'          => 'nullable|email|unique:clientes,email',
            'telefono'       => 'nullable|string|max:20',
            'direccion'      => 'nullable|string',
            'ruc_nit'        => 'nullable|string|max:30',
            'tipo_precio'    => 'required|in:' . implode(',', \App\Models\CatalogoValor::codigos('clientes.tipo_precio')),
            'limite_credito' => 'nullable|numeric|min:0',
            'expuesto_publicamente' => 'boolean',
            'funcionario'    => 'boolean',
        ]);
        $validated['expuesto_publicamente'] = $request->boolean('expuesto_publicamente');
        $validated['funcionario'] = $request->boolean('funcionario');

        $cliente = Cliente::create($validated);
        $cliente->guardarCamposPersonalizados($request->input('campos_personalizados', []));
        $cliente->sincronizarEtiquetas($request->input('etiquetas'));

        return redirect()->route('clientes.index')
            ->with('exito', 'Cliente creado exitosamente.');
    }

    public function show(Cliente $cliente)
    {
        $cliente->setRelation('pedidos', $cliente->pedidos()->orderBy('created_at', 'desc')->limit(10)->get());

        $saldoPendiente = (float) ($cliente->pedidos()->selectRaw('SUM(total - monto_pagado)')->value('SUM(total - monto_pagado)') ?? 0);

        $resumen = [
            'total_pedidos'   => $cliente->pedidos()->count(),
            'total_comprado'  => $cliente->pedidos()->sum('total'),
            'saldo_pendiente' => $saldoPendiente,
        ];

        $campos = $cliente->camposPersonalizadosDisponibles();
        $valoresCamposPersonalizados = $cliente->valoresCamposPersonalizadosPorNombre();
        $contactos = $cliente->contactos;
        $interacciones = $cliente->interacciones()->with('usuario')->get();
        $documentos = $cliente->documentosAdjuntos()->with('usuario')->get();
        $etiquetas = $cliente->etiquetas;
        $lineaDeTiempo = $this->construirLineaDeTiempo($cliente, $interacciones);

        return view('clientes.detalle', compact(
            'cliente', 'resumen', 'campos', 'valoresCamposPersonalizados',
            'contactos', 'interacciones', 'documentos', 'etiquetas', 'lineaDeTiempo'
        ));
    }

    private function construirLineaDeTiempo(Cliente $cliente, $interacciones): \Illuminate\Support\Collection
    {
        $eventos = collect();

        foreach ($cliente->pedidos()->latest()->limit(15)->get() as $p) {
            $eventos->push(['fecha' => $p->fecha_pedido, 'tipo' => 'Pedido', 'icono' => 'bi-cart3', 'texto' => "Pedido {$p->numero_referencia} por " . number_format($p->total, 0, ',', '.')]);
        }
        foreach (\App\Models\Factura::whereHas('pedido', fn($q) => $q->where('cliente_id', $cliente->id))->latest()->limit(15)->get() as $f) {
            $eventos->push(['fecha' => $f->fecha_factura, 'tipo' => 'Factura', 'icono' => 'bi-receipt', 'texto' => "Factura {$f->numero_documento} por " . number_format($f->total, 0, ',', '.')]);
        }
        foreach (\App\Models\Pago::whereHas('pedido', fn($q) => $q->where('cliente_id', $cliente->id))->latest()->limit(15)->get() as $pg) {
            $eventos->push(['fecha' => $pg->fecha_pago, 'tipo' => 'Pago', 'icono' => 'bi-cash-coin', 'texto' => "Pago de " . number_format($pg->monto, 0, ',', '.')]);
        }
        foreach ($interacciones as $i) {
            $eventos->push(['fecha' => $i->fecha, 'tipo' => 'Interacción', 'icono' => 'bi-chat-dots', 'texto' => \App\Models\CatalogoValor::etiqueta('interacciones.tipo', $i->tipo) . ': ' . $i->descripcion]);
        }

        return $eventos->sortByDesc('fecha')->take(20)->values();
    }

    public function edit(Cliente $cliente)
    {
        $campos = $cliente->camposPersonalizadosDisponibles();
        $valores = $cliente->valoresCamposPersonalizadosPorNombre();
        $etiquetasTexto = $cliente->etiquetas->pluck('nombre')->implode(', ');
        return view('clientes.editar', compact('cliente', 'campos', 'valores', 'etiquetasTexto'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        $validated = $request->validate([
            'nombre'         => 'required|string|max:100',
            'email'          => ['nullable', 'email', Rule::unique('clientes')->ignore($cliente->id)],
            'telefono'       => 'nullable|string|max:20',
            'direccion'      => 'nullable|string',
            'ruc_nit'        => 'nullable|string|max:30',
            'tipo_precio'    => 'required|in:' . implode(',', \App\Models\CatalogoValor::codigos('clientes.tipo_precio')),
            'activo'         => 'boolean',
            'limite_credito' => 'nullable|numeric|min:0',
            'expuesto_publicamente' => 'boolean',
            'funcionario'    => 'boolean',
        ]);
        $validated['expuesto_publicamente'] = $request->boolean('expuesto_publicamente');
        $validated['funcionario'] = $request->boolean('funcionario');

        $cliente->update($validated);
        $cliente->guardarCamposPersonalizados($request->input('campos_personalizados', []));
        $cliente->sincronizarEtiquetas($request->input('etiquetas'));

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
