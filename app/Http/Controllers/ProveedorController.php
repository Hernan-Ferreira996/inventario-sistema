<?php
namespace App\Http\Controllers;
use App\Models\Proveedor;
use App\Models\PedidoCompra;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function index(Request $request)
    {
        $query = Proveedor::query();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($w) use ($q) {
                $w->where('nombre', 'like', "%{$q}%")
                  ->orWhere('email', 'like', "%{$q}%")
                  ->orWhere('ruc_nit', 'like', "%{$q}%");
            });
        }

        $proveedores = $query->orderBy('nombre')->paginate(20)->withQueryString();
        $proveedores->each(fn($p) => $p->pedidos_compra_count = $p->pedidosCompra()->count());
        return view('proveedores.lista', compact('proveedores'));
    }

    public function create()
    {
        return view('proveedores.crear');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'    => 'required|string|max:150',
            'email'     => 'nullable|email|max:150|unique:proveedores,email',
            'telefono'  => 'nullable|string|max:30',
            'direccion' => 'nullable|string|max:255',
            'ruc_nit'   => 'nullable|string|max:30',
            'contacto'  => 'nullable|string|max:100',
        ]);
        $data['activo'] = true;
        Proveedor::create($data);
        return redirect()->route('proveedores.index')->with('success', 'Proveedor creado correctamente.');
    }

    public function show(Proveedor $proveedor)
    {
        $proveedorId = $proveedor->id;
        $pedidos_compra_count = PedidoCompra::where('proveedor_id', $proveedorId)->count();
        $proveedor->pedidos_compra_count = $pedidos_compra_count;
        $ultimas = PedidoCompra::where('proveedor_id', $proveedorId)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        return view('proveedores.detalle', compact('proveedor', 'ultimas'));
    }

    public function edit(Proveedor $proveedor)
    {
        return view('proveedores.editar', compact('proveedor'));
    }

    public function update(Request $request, Proveedor $proveedor)
    {
        $data = $request->validate([
            'nombre'    => 'required|string|max:150',
            'email'     => 'nullable|email|max:150|unique:proveedores,email,'.$proveedor->id,
            'telefono'  => 'nullable|string|max:30',
            'direccion' => 'nullable|string|max:255',
            'ruc_nit'   => 'nullable|string|max:30',
            'contacto'  => 'nullable|string|max:100',
        ]);
        $data['activo'] = $request->boolean('activo');
        $proveedor->update($data);
        return redirect()->route('proveedores.show', $proveedor)->with('success', 'Proveedor actualizado.');
    }

    public function destroy(Proveedor $proveedor)
    {
        $proveedor->delete();
        return redirect()->route('proveedores.index')->with('success', 'Proveedor eliminado.');
    }
}