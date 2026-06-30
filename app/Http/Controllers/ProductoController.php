<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Unidad;
use App\Models\Impuesto;
use App\Models\Ubicacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $query = Producto::with(['categoria', 'unidad', 'impuesto'])
            ->activos()
            ->withSum('movimientos', 'cantidad');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($w) use ($q) {
                $w->where('nombre', 'like', "%{$q}%")
                  ->orWhere('codigo', 'like', "%{$q}%");
            });
        }
        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }
        if ($request->filled('stock_bajo')) {
            $minimo = (int) (\App\Support\Configuracion::obtener()['sistema_stock_minimo'] ?? 5);
            $query->having('movimientos_sum_cantidad', '<=', $minimo);
        }

        $productos = $query->orderBy('nombre')->paginate(25)->withQueryString();

        $categorias = Categoria::where('activo', true)->orderBy('nombre')->get();

        $totales = [
            'cantidad_productos' => Producto::activos()->count(),
            'stock_total'        => Producto::activos()->withSum('movimientos', 'cantidad')->get()->sum('movimientos_sum_cantidad'),
        ];

        return view('productos.lista', compact('productos', 'totales', 'categorias'));
    }

    public function create()
    {
        $categorias = Categoria::where('activo', true)->orderBy('nombre')->get();
        $unidades   = Unidad::orderBy('nombre')->get();
        $impuestos  = Impuesto::orderBy('nombre')->get();
        $ubicaciones = Ubicacion::where('activo', true)->orderBy('nombre')->get();

        return view('productos.crear', compact('categorias', 'unidades', 'impuestos', 'ubicaciones'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'codigo'                   => 'required|string|max:30|unique:productos,codigo',
            'nombre'                   => 'required|string|max:100',
            'descripcion'              => 'nullable|string',
            'categoria_id'             => 'nullable|exists:categorias,id',
            'unidad_id'                => 'nullable|exists:unidades,id',
            'impuesto_id'              => 'nullable|exists:impuestos,id',
            'precio_compra'            => 'required|numeric|min:0',
            'precio_venta_minorista'   => 'required|numeric|min:0',
            'precio_venta_mayorista'   => 'required|numeric|min:0',
            'imagen'                   => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('imagen')) {
            $validated['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        $validated['codigo'] = strtoupper($validated['codigo']);

        Producto::create($validated);

        return redirect()->route('productos.index')
            ->with('exito', 'Producto creado exitosamente.');
    }

    public function show(Producto $producto)
    {
        $producto->load(['categoria', 'unidad', 'impuesto']);

        $stockPorUbicacion = $producto->movimientos()
            ->with('ubicacion')
            ->selectRaw('ubicacion_id, SUM(cantidad) as total')
            ->groupBy('ubicacion_id')
            ->get();

        $historialMovimientos = $producto->movimientos()
            ->with(['ubicacion', 'usuario'])
            ->latest('fecha_movimiento')
            ->take(20)
            ->get();

        return view('productos.detalle', compact('producto', 'stockPorUbicacion', 'historialMovimientos'));
    }

    public function edit(Producto $producto)
    {
        $categorias  = Categoria::where('activo', true)->orderBy('nombre')->get();
        $unidades    = Unidad::orderBy('nombre')->get();
        $impuestos   = Impuesto::orderBy('nombre')->get();

        return view('productos.editar', compact('producto', 'categorias', 'unidades', 'impuestos'));
    }

    public function update(Request $request, Producto $producto)
    {
        $validated = $request->validate([
            'codigo'                 => ['required', 'string', 'max:30', Rule::unique('productos')->ignore($producto->id)],
            'nombre'                 => 'required|string|max:100',
            'descripcion'            => 'nullable|string',
            'categoria_id'           => 'nullable|exists:categorias,id',
            'unidad_id'              => 'nullable|exists:unidades,id',
            'impuesto_id'            => 'nullable|exists:impuestos,id',
            'precio_compra'          => 'required|numeric|min:0',
            'precio_venta_minorista' => 'required|numeric|min:0',
            'precio_venta_mayorista' => 'required|numeric|min:0',
            'imagen'                 => 'nullable|image|max:2048',
            'activo'                 => 'boolean',
        ]);

        if ($request->hasFile('imagen')) {
            if ($producto->imagen) {
                Storage::disk('public')->delete($producto->imagen);
            }
            $validated['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        $producto->update($validated);

        return redirect()->route('productos.show', $producto)
            ->with('exito', 'Producto actualizado exitosamente.');
    }

    public function destroy(Producto $producto)
    {
        $producto->delete();

        return redirect()->route('productos.index')
            ->with('exito', 'Producto eliminado exitosamente.');
    }

    public function ajustarStock(Request $request, Producto $producto)
    {
        $request->validate([
            'cantidad'    => 'required|numeric|not_in:0',
            'ubicacion_id' => 'required|exists:ubicaciones,id',
            'tipo'         => 'required|in:entrada,salida,ajuste',
            'referencia'   => 'nullable|string|max:100',
            'notas'        => 'nullable|string',
        ]);

        $cantidad = $request->tipo === 'salida'
            ? -abs($request->cantidad)
            : abs($request->cantidad);

        if ($request->tipo === 'salida') {
            $stockActual = $producto->stockEnUbicacion($request->ubicacion_id);
            if ($stockActual < abs($request->cantidad)) {
                return back()->with('error', "Stock insuficiente. Disponible: $stockActual");
            }
        }

        $producto->movimientos()->create([
            'ubicacion_id'     => $request->ubicacion_id,
            'usuario_id'       => auth()->id(),
            'cantidad'         => $cantidad,
            'tipo'             => $request->tipo,
            'referencia'       => $request->referencia,
            'notas'            => $request->notas,
            'fecha_movimiento' => now(),
        ]);

        return back()->with('exito', 'Stock actualizado exitosamente.');
    }
}
