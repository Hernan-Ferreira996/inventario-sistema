<?php

namespace App\Http\Controllers;

use App\Events\FacturaProveedorRegistrada;
use App\Models\CentroCosto;
use App\Models\CuotaFacturaProveedor;
use App\Models\FacturaProveedor;
use App\Models\Proveedor;
use App\Support\Cierre;
use App\Support\Numeracion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FacturaProveedorController extends Controller
{
    public function index(Request $request)
    {
        $query = FacturaProveedor::with(['proveedor', 'centroCosto']);

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($w) use ($q) {
                $w->where('numero_referencia', 'like', "%{$q}%")
                  ->orWhere('numero_factura_proveedor', 'like', "%{$q}%")
                  ->orWhereHas('proveedor', fn($p) => $p->where('nombre', 'like', "%{$q}%"));
            });
        }
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $facturas = $query->latest('fecha_emision')->paginate(20)->withQueryString();

        return view('facturas-proveedor.lista', compact('facturas'));
    }

    public function create()
    {
        $proveedores = Proveedor::where('activo', true)->orderBy('nombre')->get();
        $centrosCosto = CentroCosto::where('activo', true)->orderBy('codigo')->get();
        $proximoNumero = Numeracion::previsualizar('facturas_proveedor', null, 'FP-');

        return view('facturas-proveedor.crear', compact('proveedores', 'centrosCosto', 'proximoNumero'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'proveedor_id'              => 'required|exists:proveedores,id',
            'centro_costo_id'           => 'nullable|exists:centros_costo,id',
            'numero_factura_proveedor'  => 'required|string|max:30',
            'timbrado_proveedor'        => 'nullable|string|max:20',
            'ruc_proveedor'             => 'nullable|string|max:30',
            'fecha_emision'             => 'required|date',
            'fecha_vencimiento'         => 'nullable|date|after_or_equal:fecha_emision',
            'iva_porcentaje'            => 'required|numeric|min:0|max:100',
            'retiene_iva'               => 'boolean',
            'retencion_timbrado'        => 'nullable|string|max:20',
            'retencion_numero'          => 'nullable|string|max:20',
            'retencion_porcentaje'      => 'nullable|numeric|min:0|max:100',
            'retencion_monto'           => 'nullable|numeric|min:0',
            'cantidad_cuotas'           => 'required|integer|min:1|max:36',
            'dias_entre_cuotas'         => 'required|integer|min:1|max:365',
            'observaciones'             => 'nullable|string',
            'lineas'                    => 'required|array|min:1',
            'lineas.*.concepto'         => 'required|string|max:255',
            'lineas.*.centro_costo_id'  => 'nullable|exists:centros_costo,id',
            'lineas.*.cantidad'         => 'required|numeric|min:0.01',
            'lineas.*.precio_unitario'  => 'required|numeric|min:0',
        ]);

        $retieneIva = $request->boolean('retiene_iva');

        $subtotal = 0;
        foreach ($validated['lineas'] as $linea) {
            $subtotal += $linea['cantidad'] * $linea['precio_unitario'];
        }
        $ivaTotal = round($subtotal * ($validated['iva_porcentaje'] / 100), 2);
        $total = $subtotal + $ivaTotal;
        $retencionMonto = $retieneIva ? (float) ($validated['retencion_monto'] ?? 0) : 0;

        $factura = DB::transaction(function () use ($request, $validated, $subtotal, $ivaTotal, $total, $retieneIva, $retencionMonto) {
            $factura = FacturaProveedor::create([
                'proveedor_id'             => $validated['proveedor_id'],
                'centro_costo_id'          => $validated['centro_costo_id'] ?? null,
                'usuario_id'               => Auth::id(),
                'numero_referencia'        => Numeracion::siguiente('facturas_proveedor', null, 'FP-'),
                'numero_factura_proveedor' => $validated['numero_factura_proveedor'],
                'timbrado_proveedor'       => $validated['timbrado_proveedor'] ?? null,
                'ruc_proveedor'            => $validated['ruc_proveedor'] ?? null,
                'fecha_emision'            => $validated['fecha_emision'],
                'fecha_vencimiento'        => $validated['fecha_vencimiento'] ?? null,
                'subtotal'                 => $subtotal,
                'iva_total'                => $ivaTotal,
                'total'                    => $total,
                'retiene_iva'              => $retieneIva,
                'retencion_timbrado'       => $retieneIva ? ($validated['retencion_timbrado'] ?? null) : null,
                'retencion_numero'         => $retieneIva ? ($validated['retencion_numero'] ?? null) : null,
                'retencion_porcentaje'     => $retieneIva ? ($validated['retencion_porcentaje'] ?? null) : null,
                'retencion_monto'          => $retencionMonto,
                'estado'                   => 'pendiente',
                'observaciones'            => $validated['observaciones'] ?? null,
            ]);

            foreach ($validated['lineas'] as $linea) {
                $factura->detalles()->create([
                    'centro_costo_id' => $linea['centro_costo_id'] ?? null,
                    'concepto'        => $linea['concepto'],
                    'cantidad'        => $linea['cantidad'],
                    'precio_unitario' => $linea['precio_unitario'],
                    'subtotal'        => $linea['cantidad'] * $linea['precio_unitario'],
                ]);
            }

            $this->generarCuotas($factura, $validated['cantidad_cuotas'], $validated['dias_entre_cuotas']);

            return $factura;
        });

        event(new FacturaProveedorRegistrada($factura));

        return redirect()->route('facturas-proveedor.show', $factura)->with('success', 'Factura de proveedor registrada correctamente.');
    }

    private function generarCuotas(FacturaProveedor $factura, int $cantidad, int $diasEntreCuotas): void
    {
        $fechaBase = $factura->fecha_vencimiento ?? $factura->fecha_emision;
        $montoBase = round($factura->total / $cantidad, 2);
        $acumulado = 0;

        for ($i = 1; $i <= $cantidad; $i++) {
            $esUltima = $i === $cantidad;
            $monto = $esUltima ? round($factura->total - $acumulado, 2) : $montoBase;
            $acumulado += $monto;

            CuotaFacturaProveedor::create([
                'factura_proveedor_id' => $factura->id,
                'numero_cuota'         => $i,
                'fecha_vencimiento'    => $fechaBase->copy()->addDays(($i - 1) * $diasEntreCuotas),
                'monto'                => $monto,
            ]);
        }
    }

    public function show(FacturaProveedor $facturaProveedor)
    {
        $facturaProveedor->load(['proveedor', 'centroCosto', 'usuario', 'detalles.centroCosto', 'cuotas']);

        return view('facturas-proveedor.detalle', compact('facturaProveedor'));
    }

    private function esEditable(FacturaProveedor $factura): bool
    {
        return $factura->estado === 'pendiente' && (float) $factura->monto_pagado <= 0;
    }

    public function edit(FacturaProveedor $facturaProveedor)
    {
        if (!$this->esEditable($facturaProveedor)) {
            return redirect()->route('facturas-proveedor.show', $facturaProveedor)
                ->with('error', 'No se puede editar una factura con pagos registrados.');
        }

        $facturaProveedor->load('detalles', 'cuotas');
        $proveedores = Proveedor::where('activo', true)->orderBy('nombre')->get();
        $centrosCosto = CentroCosto::where('activo', true)->orderBy('codigo')->get();

        return view('facturas-proveedor.editar', compact('facturaProveedor', 'proveedores', 'centrosCosto'));
    }

    public function update(Request $request, FacturaProveedor $facturaProveedor)
    {
        if (!$this->esEditable($facturaProveedor)) {
            return redirect()->route('facturas-proveedor.show', $facturaProveedor)
                ->with('error', 'No se puede editar una factura con pagos registrados.');
        }

        $validated = $request->validate([
            'proveedor_id'              => 'required|exists:proveedores,id',
            'centro_costo_id'           => 'nullable|exists:centros_costo,id',
            'numero_factura_proveedor'  => 'required|string|max:30',
            'timbrado_proveedor'        => 'nullable|string|max:20',
            'ruc_proveedor'             => 'nullable|string|max:30',
            'fecha_emision'             => 'required|date',
            'fecha_vencimiento'         => 'nullable|date|after_or_equal:fecha_emision',
            'iva_porcentaje'            => 'required|numeric|min:0|max:100',
            'retiene_iva'               => 'boolean',
            'retencion_timbrado'        => 'nullable|string|max:20',
            'retencion_numero'          => 'nullable|string|max:20',
            'retencion_porcentaje'      => 'nullable|numeric|min:0|max:100',
            'retencion_monto'           => 'nullable|numeric|min:0',
            'cantidad_cuotas'           => 'required|integer|min:1|max:36',
            'dias_entre_cuotas'         => 'required|integer|min:1|max:365',
            'observaciones'             => 'nullable|string',
            'lineas'                    => 'required|array|min:1',
            'lineas.*.concepto'         => 'required|string|max:255',
            'lineas.*.centro_costo_id'  => 'nullable|exists:centros_costo,id',
            'lineas.*.cantidad'         => 'required|numeric|min:0.01',
            'lineas.*.precio_unitario'  => 'required|numeric|min:0',
        ]);

        $retieneIva = $request->boolean('retiene_iva');

        $subtotal = 0;
        foreach ($validated['lineas'] as $linea) {
            $subtotal += $linea['cantidad'] * $linea['precio_unitario'];
        }
        $ivaTotal = round($subtotal * ($validated['iva_porcentaje'] / 100), 2);
        $total = $subtotal + $ivaTotal;
        $retencionMonto = $retieneIva ? (float) ($validated['retencion_monto'] ?? 0) : 0;

        DB::transaction(function () use ($request, $validated, $facturaProveedor, $subtotal, $ivaTotal, $total, $retieneIva, $retencionMonto) {
            $facturaProveedor->update([
                'proveedor_id'             => $validated['proveedor_id'],
                'centro_costo_id'          => $validated['centro_costo_id'] ?? null,
                'numero_factura_proveedor' => $validated['numero_factura_proveedor'],
                'timbrado_proveedor'       => $validated['timbrado_proveedor'] ?? null,
                'ruc_proveedor'            => $validated['ruc_proveedor'] ?? null,
                'fecha_emision'            => $validated['fecha_emision'],
                'fecha_vencimiento'        => $validated['fecha_vencimiento'] ?? null,
                'subtotal'                 => $subtotal,
                'iva_total'                => $ivaTotal,
                'total'                    => $total,
                'retiene_iva'              => $retieneIva,
                'retencion_timbrado'       => $retieneIva ? ($validated['retencion_timbrado'] ?? null) : null,
                'retencion_numero'         => $retieneIva ? ($validated['retencion_numero'] ?? null) : null,
                'retencion_porcentaje'     => $retieneIva ? ($validated['retencion_porcentaje'] ?? null) : null,
                'retencion_monto'          => $retencionMonto,
                'observaciones'            => $validated['observaciones'] ?? null,
            ]);

            $facturaProveedor->detalles()->delete();
            foreach ($validated['lineas'] as $linea) {
                $facturaProveedor->detalles()->create([
                    'centro_costo_id' => $linea['centro_costo_id'] ?? null,
                    'concepto'        => $linea['concepto'],
                    'cantidad'        => $linea['cantidad'],
                    'precio_unitario' => $linea['precio_unitario'],
                    'subtotal'        => $linea['cantidad'] * $linea['precio_unitario'],
                ]);
            }

            $facturaProveedor->cuotas()->delete();
            $this->generarCuotas($facturaProveedor, $validated['cantidad_cuotas'], $validated['dias_entre_cuotas']);
        });

        return redirect()->route('facturas-proveedor.show', $facturaProveedor)->with('success', 'Factura de proveedor actualizada.');
    }

    public function destroy(FacturaProveedor $facturaProveedor)
    {
        if (Cierre::estaBloqueada($facturaProveedor->fecha_emision)) {
            return redirect()->route('facturas-proveedor.index')->with('error', Cierre::mensajeBloqueo());
        }

        if ((float) $facturaProveedor->monto_pagado > 0) {
            return redirect()->route('facturas-proveedor.index')
                ->with('error', 'No se puede eliminar una factura con pagos registrados.');
        }

        $facturaProveedor->delete();

        return redirect()->route('facturas-proveedor.index')->with('success', 'Factura de proveedor eliminada.');
    }

    public function marcarCuotaPagada(CuotaFacturaProveedor $cuota)
    {
        if ($cuota->pagada) {
            return redirect()->back()->with('error', 'Esta cuota ya está marcada como pagada.');
        }

        DB::transaction(function () use ($cuota) {
            $cuota->update(['pagada' => true, 'fecha_pago' => now()->toDateString()]);

            $factura = $cuota->facturaProveedor;
            $factura->increment('monto_pagado', $cuota->monto);
            $factura->refresh();

            $factura->update([
                'estado' => (float) $factura->monto_pagado >= (float) $factura->total ? 'pagada' : 'parcial',
            ]);
        });

        return redirect()->route('facturas-proveedor.show', $cuota->factura_proveedor_id)->with('success', 'Cuota marcada como pagada.');
    }
}
