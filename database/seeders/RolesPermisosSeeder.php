<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesPermisosSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $modulos = [
            'productos', 'categorias', 'clientes', 'proveedores',
            'presupuestos', 'pedidos', 'compras', 'facturas', 'pagos', 'envios',
            'reportes', 'usuarios', 'configuracion', 'contabilidad', 'centros_costo',
            'facturas_proveedor',
        ];
        $acciones = ['ver', 'crear', 'editar', 'eliminar'];

        foreach ($modulos as $modulo) {
            foreach ($acciones as $accion) {
                Permission::firstOrCreate(['name' => "{$modulo}.{$accion}"]);
            }
        }
        // Permisos especiales (no CRUD estándar)
        foreach (['productos.ver_costos', 'reportes.compras', 'reportes.exportar'] as $permiso) {
            Permission::firstOrCreate(['name' => $permiso]);
        }

        // ===== ADMIN: todo =====
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions(Permission::all());

        // ===== VENDEDOR: ventas + clientes, sin costos ni compras =====
        $vendedor = Role::firstOrCreate(['name' => 'vendedor']);
        $vendedor->syncPermissions([
            'productos.ver',
            'categorias.ver',
            'clientes.ver', 'clientes.crear', 'clientes.editar',
            'presupuestos.ver', 'presupuestos.crear', 'presupuestos.editar',
            'pedidos.ver', 'pedidos.crear', 'pedidos.editar',
            'facturas.ver', 'facturas.crear',
            'pagos.ver', 'pagos.crear',
            'envios.ver', 'envios.crear',
            'reportes.ver',
        ]);

        // ===== BODEGUERO: stock y compras, sin precios de venta ni datos de clientes =====
        $bodeguero = Role::firstOrCreate(['name' => 'bodeguero']);
        $bodeguero->syncPermissions([
            'productos.ver', 'productos.crear', 'productos.editar',
            'categorias.ver', 'categorias.crear', 'categorias.editar',
            'proveedores.ver', 'proveedores.crear', 'proveedores.editar',
            'compras.ver', 'compras.crear', 'compras.editar',
            'pedidos.ver', // necesario para ubicar el pedido al despachar (Notas de Remisión/Envíos)
            'envios.ver', 'envios.crear', 'envios.editar',
            'reportes.ver', 'reportes.compras',
            'centros_costo.ver', // necesario para imputar centro de costo al crear una compra
        ]);

        // ===== CONTADOR: solo lectura + pagos/facturas + reportes, sin crear/editar operativo =====
        $contador = Role::firstOrCreate(['name' => 'contador']);
        $contador->syncPermissions([
            'productos.ver', 'productos.ver_costos',
            'clientes.ver', 'proveedores.ver',
            'presupuestos.ver', 'pedidos.ver', 'compras.ver',
            'facturas.ver', 'facturas.crear',
            'pagos.ver', 'pagos.crear', 'pagos.editar',
            'reportes.ver', 'reportes.compras', 'reportes.exportar',
            'configuracion.ver',
            'contabilidad.ver', 'contabilidad.crear', 'contabilidad.editar',
            'centros_costo.ver', 'centros_costo.crear', 'centros_costo.editar',
            'facturas_proveedor.ver', 'facturas_proveedor.crear', 'facturas_proveedor.editar',
        ]);

        // Asignar rol admin al usuario admin existente
        $adminUser = User::where('email', 'admin@inventario.com')->first();
        if ($adminUser && !$adminUser->hasRole('admin')) {
            $adminUser->assignRole('admin');
        }
    }
}
