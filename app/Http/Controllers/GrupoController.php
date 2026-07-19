<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class GrupoController extends Controller
{
    private function moduleMatrix(): array
    {
        return [
            'Catálogos' => [
                'productos'  => 'Productos',
                'categorias' => 'Categorías',
                'unidades'   => 'Unidades',
                'impuestos'  => 'Impuestos',
                'ubicaciones'=> 'Ubicaciones',
            ],
            'Comercial' => [
                'presupuestos' => 'Presupuestos',
                'pedidos'      => 'Pedidos de Venta',
                'clientes'     => 'Clientes',
                'facturas'     => 'Facturas',
                'pagos'        => 'Pagos',
                'envios'       => 'Envíos',
                'notas_credito'=> 'Notas de Crédito',   // mapped via special perms
                'notas_remision'=> 'Notas de Remisión', // mapped via envios
            ],
            'Compras' => [
                'compras'            => 'Compras',
                'proveedores'        => 'Proveedores',
                'traslados'          => 'Traslados de Stock',
                'facturas_proveedor' => 'Facturas de Proveedor',
            ],
            'Reportes' => [
                'reportes' => 'Reportes',
            ],
            'Contabilidad' => [
                'contabilidad'  => 'Plan de Cuentas y Asientos',
                'centros_costo' => 'Centros de Costo',
            ],
            'Administración' => [
                'usuarios'       => 'Usuarios',
                'configuracion'  => 'Configuración',
            ],
        ];
    }

    public function index()
    {
        $grupos = Role::withCount('users')->orderBy('name')->get();
        return view('grupos.lista', compact('grupos'));
    }

    public function create()
    {
        // GrupoController::create solo crea el nombre, los permisos se gestionan en show()
        return view('grupos.crear');
    }

    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required|string|max:80|unique:roles,name']);

        Role::create(['name' => $request->nombre, 'guard_name' => 'web']);

        return redirect()->route('grupos.index')->with('success', "Grupo '{$request->nombre}' creado correctamente.");
    }

    public function show(Role $grupo)
    {
        $grupo->load(['permissions', 'users']);
        $matriz      = $this->moduleMatrix();
        $acciones    = ['ver', 'crear', 'editar', 'eliminar'];
        $especiales  = ['reportes.compras', 'reportes.exportar', 'productos.ver_costos'];
        $permisosGrupo = $grupo->permissions->pluck('name')->toArray();

        // Detectar permisos en BD que no están en la matriz conocida
        $todosEnMatriz = collect($matriz)->flatMap(fn($mods) => array_keys($mods))
            ->flatMap(fn($mod) => array_map(fn($acc) => "{$mod}.{$acc}", $acciones))
            ->merge($especiales)->toArray();

        $permisosDesconocidos = Permission::whereNotIn('name', $todosEnMatriz)->get();

        return view('grupos.detalle', compact('grupo', 'matriz', 'acciones', 'especiales', 'permisosGrupo', 'permisosDesconocidos'));
    }

    public function edit(Role $grupo)
    {
        return view('grupos.editar', compact('grupo'));
    }

    public function update(Request $request, Role $grupo)
    {
        if ($grupo->name === 'admin') {
            return redirect()->route('grupos.show', $grupo)->with('error', 'El grupo admin no puede renombrarse.');
        }
        $request->validate(['nombre' => 'required|string|max:80|unique:roles,name,' . $grupo->id]);
        $grupo->update(['name' => $request->nombre]);
        return redirect()->route('grupos.show', $grupo)->with('success', 'Grupo actualizado.');
    }

    public function updatePermisos(Request $request, Role $grupo)
    {
        $permisos = $request->input('permisos', []);
        $grupo->syncPermissions($permisos);
        return redirect()->route('grupos.show', $grupo)->with('success', "Permisos del grupo '{$grupo->name}' actualizados.");
    }

    public function destroy(Role $grupo)
    {
        if (in_array($grupo->name, ['admin'])) {
            return redirect()->route('grupos.index')->with('error', 'El grupo admin no puede eliminarse.');
        }
        if ($grupo->users()->count() > 0) {
            return redirect()->route('grupos.index')
                ->with('error', "No se puede eliminar el grupo '{$grupo->name}': tiene {$grupo->users()->count()} usuario(s) asignados.");
        }
        $grupo->delete();
        return redirect()->route('grupos.index')->with('success', 'Grupo eliminado.');
    }
}
