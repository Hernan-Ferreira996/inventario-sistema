<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Categoria;
use App\Models\Unidad;
use App\Models\Impuesto;
use App\Models\Ubicacion;
use App\Models\Producto;
use App\Models\Cliente;
use App\Models\Proveedor;
use App\Models\TerminoPago;
use App\Models\MetodoPago;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(['email' => 'admin@inventario.com'], [
            'name'     => 'Administrador',
            'password' => Hash::make('password'),
        ]);

        $this->call(RolesPermisosSeeder::class);
        $this->call(ModulosPlanesSeeder::class);
        $this->call(CatalogoValoresSeeder::class);
        $this->call(SecuenciasDocumentoSeeder::class);
        $this->call(PlanDeCuentasSeeder::class);

        $demo = [
            ['email' => 'admin.empresa@inventario.com', 'name' => 'Administrador de Empresa', 'rol' => 'admin'],
            ['email' => 'vendedor@inventario.com',  'name' => 'Carla Vendedora', 'rol' => 'vendedor'],
            ['email' => 'bodeguero@inventario.com',  'name' => 'Luis Bodeguero',  'rol' => 'bodeguero'],
            ['email' => 'contador@inventario.com',   'name' => 'Ana Contadora',   'rol' => 'contador'],
        ];
        foreach ($demo as $d) {
            $user = User::firstOrCreate(['email' => $d['email']], [
                'name'     => $d['name'],
                'password' => Hash::make('password'),
            ]);
            if (!$user->hasRole($d['rol'])) {
                $user->assignRole($d['rol']);
            }
        }

        $categorias = ['Electrónica', 'Ropa y Calzado', 'Alimentos', 'Bebidas', 'Herramientas', 'Oficina', 'Limpieza', 'Medicamentos'];
        foreach ($categorias as $cat) {
            Categoria::create(['nombre' => $cat]);
        }

        $unidades = [
            ['nombre' => 'Unidad',    'abreviatura' => 'und'],
            ['nombre' => 'Kilogramo', 'abreviatura' => 'kg'],
            ['nombre' => 'Litro',     'abreviatura' => 'lt'],
            ['nombre' => 'Caja',      'abreviatura' => 'cja'],
            ['nombre' => 'Par',       'abreviatura' => 'par'],
            ['nombre' => 'Metro',     'abreviatura' => 'm'],
            ['nombre' => 'Docena',    'abreviatura' => 'doc'],
        ];
        foreach ($unidades as $u) {
            Unidad::create($u);
        }

        Impuesto::insert([
            ['nombre' => 'Sin impuesto', 'porcentaje' => 0,  'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'IVA 12%',      'porcentaje' => 12, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'IVA 5%',       'porcentaje' => 5,  'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'IVA 19%',      'porcentaje' => 19, 'created_at' => now(), 'updated_at' => now()],
        ]);

        $ubicaciones = [
            ['codigo' => 'ALMACEN-01', 'nombre' => 'Almacén Principal'],
            ['codigo' => 'ALMACEN-02', 'nombre' => 'Almacén Secundario'],
            ['codigo' => 'TIENDA-01',  'nombre' => 'Tienda Principal'],
            ['codigo' => 'BODEGA-01',  'nombre' => 'Bodega Norte'],
        ];
        foreach ($ubicaciones as $u) {
            Ubicacion::create($u);
        }

        foreach (['Efectivo', 'Transferencia Bancaria', 'Tarjeta de Débito', 'Tarjeta de Crédito', 'Cheque'] as $mp) {
            MetodoPago::firstOrCreate(['nombre' => $mp], ['activo' => true]);
        }

        TerminoPago::insert([
            ['nombre' => 'Contado',     'dias' => 0,  'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Crédito 15d', 'dias' => 15, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Crédito 30d', 'dias' => 30, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Crédito 60d', 'dias' => 60, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Precios en guaraníes (PYG) — sin decimales, valores de referencia de mercado paraguayo
        $productos = [
            ['codigo' => 'LAPTOP-001', 'nombre' => 'Laptop HP 14"',       'categoria_id' => 1, 'unidad_id' => 1, 'impuesto_id' => 2, 'precio_compra' => 3800000, 'precio_venta_minorista' => 5200000, 'precio_venta_mayorista' => 4800000],
            ['codigo' => 'MOUSE-001',  'nombre' => 'Mouse Inalámbrico',    'categoria_id' => 1, 'unidad_id' => 1, 'impuesto_id' => 2, 'precio_compra' => 45000,   'precio_venta_minorista' => 75000,   'precio_venta_mayorista' => 65000],
            ['codigo' => 'CAMISA-001', 'nombre' => 'Camisa Polo Talla M',  'categoria_id' => 2, 'unidad_id' => 1, 'impuesto_id' => 1, 'precio_compra' => 50000,   'precio_venta_minorista' => 95000,   'precio_venta_mayorista' => 80000],
            ['codigo' => 'ARROZ-001',  'nombre' => 'Arroz 1kg',            'categoria_id' => 3, 'unidad_id' => 2, 'impuesto_id' => 3, 'precio_compra' => 4500,    'precio_venta_minorista' => 6500,    'precio_venta_mayorista' => 5800],
            ['codigo' => 'PAPEL-001',  'nombre' => 'Resma Papel A4',       'categoria_id' => 6, 'unidad_id' => 4, 'impuesto_id' => 2, 'precio_compra' => 28000,   'precio_venta_minorista' => 42000,   'precio_venta_mayorista' => 38000],
        ];
        foreach ($productos as $p) {
            Producto::create($p);
        }

        $clientes = [
            ['nombre' => 'Juan Pérez',        'email' => 'juan@email.com',    'telefono' => '0991234567', 'tipo_precio' => 'minorista'],
            ['nombre' => 'Distribuidora XYZ',  'email' => 'xyz@empresa.com',   'telefono' => '022345678',  'tipo_precio' => 'mayorista'],
            ['nombre' => 'María González',     'email' => 'maria@email.com',   'telefono' => '0987654321', 'tipo_precio' => 'minorista'],
            ['nombre' => 'Comercial ABC S.A.', 'email' => 'abc@comercial.com', 'telefono' => '023456789',  'tipo_precio' => 'mayorista'],
        ];
        foreach ($clientes as $c) {
            Cliente::create($c);
        }

        $proveedores = [
            ['nombre' => 'Importadora TechPro',   'email' => 'ventas@techpro.com',    'telefono' => '022111222'],
            ['nombre' => 'Distribuidora Nacional', 'email' => 'info@distnac.com',      'telefono' => '023333444'],
            ['nombre' => 'Agroexport Sur',         'email' => 'compras@agroexport.com','telefono' => '075556677'],
        ];
        foreach ($proveedores as $p) {
            Proveedor::create($p);
        }

        // Crea la empresa demo + sucursal principal y les asigna empresa_id a
        // todo lo sembrado arriba. Sin este paso, todos los usuarios demo
        // quedan con empresa_id null y el sistema los trata como super-admin
        // (no pueden crear pedidos/presupuestos/facturas).
        $this->call(EmpresaSeeder::class);
    }
}
