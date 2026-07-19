<?php
namespace App\Http\Controllers;
use App\Models\CampoPersonalizado;
use App\Models\Ciudad;
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
        $campos = CampoPersonalizado::paraEntidad('proveedor');
        $ciudades = Ciudad::where('activo', true)->orderBy('nombre')->get();
        return view('proveedores.crear', compact('campos', 'ciudades'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'    => 'required|string|max:150',
            'email'     => 'nullable|email|max:150|unique:proveedores,email',
            'telefono'  => 'nullable|string|max:30',
            'direccion' => 'nullable|string|max:255',
            'ciudad_id' => 'nullable|exists:ciudades,id',
            'ruc_nit'   => 'nullable|string|max:30',
            'contacto'  => 'nullable|string|max:100',
            'pais'      => 'nullable|string|max:60',
            'expuesto_publicamente' => 'boolean',
            'funcionario'           => 'boolean',
        ]);
        $data['activo'] = true;
        $data['expuesto_publicamente'] = $request->boolean('expuesto_publicamente');
        $data['funcionario'] = $request->boolean('funcionario');
        $proveedor = Proveedor::create($data);
        $proveedor->guardarCamposPersonalizados($request->input('campos_personalizados', []));
        $proveedor->sincronizarEtiquetas($request->input('etiquetas'));
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
        $campos = $proveedor->camposPersonalizadosDisponibles();
        $valoresCamposPersonalizados = $proveedor->valoresCamposPersonalizadosPorNombre();
        $contactos = $proveedor->contactos;
        $interacciones = $proveedor->interacciones()->with('usuario')->get();
        $documentos = $proveedor->documentosAdjuntos()->with('usuario')->get();
        $etiquetas = $proveedor->etiquetas;
        $lineaDeTiempo = $this->construirLineaDeTiempo($proveedor, $ultimas, $interacciones);
        return view('proveedores.detalle', compact(
            'proveedor', 'ultimas', 'campos', 'valoresCamposPersonalizados',
            'contactos', 'interacciones', 'documentos', 'etiquetas', 'lineaDeTiempo'
        ));
    }

    private function construirLineaDeTiempo(Proveedor $proveedor, $ultimasCompras, $interacciones): \Illuminate\Support\Collection
    {
        $eventos = collect();

        foreach ($ultimasCompras as $c) {
            $eventos->push(['fecha' => $c->fecha_pedido, 'tipo' => 'Compra', 'icono' => 'bi-bag-check', 'texto' => "Compra {$c->numero_referencia} por " . number_format($c->total, 0, ',', '.')]);
        }
        foreach ($interacciones as $i) {
            $eventos->push(['fecha' => $i->fecha, 'tipo' => 'Interacción', 'icono' => 'bi-chat-dots', 'texto' => \App\Models\CatalogoValor::etiqueta('interacciones.tipo', $i->tipo) . ': ' . $i->descripcion]);
        }

        return $eventos->sortByDesc('fecha')->take(20)->values();
    }

    public function edit(Proveedor $proveedor)
    {
        $campos = $proveedor->camposPersonalizadosDisponibles();
        $valores = $proveedor->valoresCamposPersonalizadosPorNombre();
        $etiquetasTexto = $proveedor->etiquetas->pluck('nombre')->implode(', ');
        $ciudades = Ciudad::where('activo', true)->orderBy('nombre')->get();
        return view('proveedores.editar', compact('proveedor', 'campos', 'valores', 'etiquetasTexto', 'ciudades'));
    }

    public function update(Request $request, Proveedor $proveedor)
    {
        $data = $request->validate([
            'nombre'    => 'required|string|max:150',
            'email'     => 'nullable|email|max:150|unique:proveedores,email,'.$proveedor->id,
            'telefono'  => 'nullable|string|max:30',
            'direccion' => 'nullable|string|max:255',
            'ciudad_id' => 'nullable|exists:ciudades,id',
            'ruc_nit'   => 'nullable|string|max:30',
            'contacto'  => 'nullable|string|max:100',
            'pais'      => 'nullable|string|max:60',
            'expuesto_publicamente' => 'boolean',
            'funcionario'           => 'boolean',
        ]);
        $data['activo'] = $request->boolean('activo');
        $data['expuesto_publicamente'] = $request->boolean('expuesto_publicamente');
        $data['funcionario'] = $request->boolean('funcionario');
        $proveedor->update($data);
        $proveedor->guardarCamposPersonalizados($request->input('campos_personalizados', []));
        $proveedor->sincronizarEtiquetas($request->input('etiquetas'));
        return redirect()->route('proveedores.show', $proveedor)->with('success', 'Proveedor actualizado.');
    }

    public function destroy(Proveedor $proveedor)
    {
        $proveedor->delete();
        return redirect()->route('proveedores.index')->with('success', 'Proveedor eliminado.');
    }
}