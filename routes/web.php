<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\PedidoVentaController;

// Raíz redirige al panel si autenticado
Route::get('/', fn() => redirect()->route('dashboard'));

// Rutas de autenticación (Breeze)
require __DIR__.'/auth.php';

// Rutas protegidas
Route::middleware(['auth', 'verified'])->group(function () {

    // Panel principal (todos los roles autenticados)
    Route::get('/panel', [DashboardController::class, 'index'])->name('dashboard');

    // Perfil de usuario
    Route::get('/perfil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/perfil', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/perfil', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ===== INVENTARIO =====
    Route::resource('productos', ProductoController::class)
        ->middlewareFor(['index', 'show'], 'permission:productos.ver')
        ->middlewareFor(['create', 'store'], 'permission:productos.crear')
        ->middlewareFor(['edit', 'update'], 'permission:productos.editar')
        ->middlewareFor('destroy', 'permission:productos.eliminar');
    Route::post('productos/{producto}/stock', [ProductoController::class, 'ajustarStock'])
        ->name('productos.stock')->middleware('permission:productos.editar');
    Route::get('api/productos/buscar', [ProductoController::class, 'buscarProducto'])
        ->name('api.productos.buscar')->middleware('permission:productos.ver');

    Route::resource('categorias', \App\Http\Controllers\CategoriaController::class)
        ->middlewareFor(['index', 'show'], 'permission:categorias.ver')
        ->middlewareFor(['create', 'store'], 'permission:categorias.crear')
        ->middlewareFor(['edit', 'update'], 'permission:categorias.editar')
        ->middlewareFor('destroy', 'permission:categorias.eliminar');
    Route::resource('unidades', \App\Http\Controllers\UnidadController::class)
        ->parameters(['unidades' => 'unidad'])
        ->middleware('permission:configuracion.editar');
    Route::resource('impuestos', \App\Http\Controllers\ImpuestoController::class)
        ->parameters(['impuestos' => 'impuesto'])
        ->middleware('permission:configuracion.editar');
    Route::resource('ubicaciones', \App\Http\Controllers\UbicacionController::class)
        ->parameters(['ubicaciones' => 'ubicacion'])
        ->middleware('permission:configuracion.editar');

    // ===== VENTAS =====
    Route::resource('pedidos', PedidoVentaController::class)
        ->middlewareFor(['index', 'show'], 'permission:pedidos.ver')
        ->middlewareFor(['create', 'store'], 'permission:pedidos.crear')
        ->middlewareFor(['edit', 'update'], 'permission:pedidos.editar')
        ->middlewareFor('destroy', 'permission:pedidos.eliminar');
    Route::get('api/pedidos/buscar-producto', [PedidoVentaController::class, 'buscarProducto'])
        ->name('api.pedidos.buscar-producto')->middleware('permission:pedidos.ver');

    Route::resource('presupuestos', \App\Http\Controllers\PresupuestoController::class)
        ->middlewareFor(['index', 'show'], 'permission:pedidos.ver')
        ->middlewareFor(['create', 'store'], 'permission:pedidos.crear')
        ->middlewareFor(['edit', 'update'], 'permission:pedidos.editar')
        ->middlewareFor('destroy', 'permission:pedidos.eliminar');
    Route::post('presupuestos/{presupuesto}/convertir', [\App\Http\Controllers\PresupuestoController::class, 'convertir'])
        ->name('presupuestos.convertir')->middleware('permission:pedidos.crear');

    Route::resource('clientes', ClienteController::class)
        ->middlewareFor(['index', 'show'], 'permission:clientes.ver')
        ->middlewareFor(['create', 'store'], 'permission:clientes.crear')
        ->middlewareFor(['edit', 'update'], 'permission:clientes.editar')
        ->middlewareFor('destroy', 'permission:clientes.eliminar');

    Route::resource('facturas', \App\Http\Controllers\FacturaController::class)
        ->only(['index', 'show', 'destroy', 'create', 'store', 'edit', 'update'])
        ->middlewareFor(['index', 'show'], 'permission:facturas.ver')
        ->middlewareFor(['create', 'store'], 'permission:facturas.crear')
        ->middlewareFor(['edit', 'update'], 'permission:facturas.crear')
        ->middlewareFor('destroy', 'permission:facturas.eliminar');
    Route::get('facturas/{factura}/pdf', [\App\Http\Controllers\FacturaController::class, 'pdf'])
        ->name('facturas.pdf')->middleware('permission:facturas.ver');

    Route::resource('notas-credito', \App\Http\Controllers\NotaCreditoController::class)
        ->only(['index', 'show', 'destroy', 'create', 'store'])
        ->parameters(['notas-credito' => 'notaCredito'])
        ->middlewareFor(['index', 'show'], 'permission:facturas.ver')
        ->middlewareFor(['create', 'store'], 'permission:facturas.crear')
        ->middlewareFor('destroy', 'permission:facturas.eliminar');
    Route::get('notas-credito/{notaCredito}/pdf', [\App\Http\Controllers\NotaCreditoController::class, 'pdf'])
        ->name('notas-credito.pdf')->middleware('permission:facturas.ver');

    Route::resource('notas-remision', \App\Http\Controllers\NotaRemisionController::class)
        ->only(['index', 'show', 'destroy', 'create', 'store'])
        ->parameters(['notas-remision' => 'notaRemision'])
        ->middlewareFor(['index', 'show'], 'permission:envios.ver')
        ->middlewareFor(['create', 'store'], 'permission:envios.crear')
        ->middlewareFor('destroy', 'permission:envios.eliminar');
    Route::get('notas-remision/{notaRemision}/pdf', [\App\Http\Controllers\NotaRemisionController::class, 'pdf'])
        ->name('notas-remision.pdf')->middleware('permission:envios.ver');
    Route::resource('pagos', \App\Http\Controllers\PagoController::class)
        ->only(['index', 'show', 'store', 'destroy', 'edit', 'update'])
        ->middlewareFor(['index', 'show'], 'permission:pagos.ver')
        ->middlewareFor('store', 'permission:pagos.crear')
        ->middlewareFor(['edit', 'update'], 'permission:pagos.editar')
        ->middlewareFor('destroy', 'permission:pagos.eliminar');
    Route::resource('envios', \App\Http\Controllers\EnvioController::class)
        ->middlewareFor(['index', 'show'], 'permission:envios.ver')
        ->middlewareFor(['create', 'store'], 'permission:envios.crear')
        ->middlewareFor(['edit', 'update'], 'permission:envios.editar')
        ->middlewareFor('destroy', 'permission:envios.eliminar');

    // ===== COMPRAS =====
    Route::resource('compras', \App\Http\Controllers\PedidoCompraController::class)
        ->parameters(['compras' => 'pedidoCompra'])
        ->middlewareFor(['index', 'show'], 'permission:compras.ver')
        ->middlewareFor(['create', 'store'], 'permission:compras.crear')
        ->middlewareFor(['edit', 'update'], 'permission:compras.editar')
        ->middlewareFor('destroy', 'permission:compras.eliminar');
    Route::post('compras/{pedidoCompra}/recibir', [\App\Http\Controllers\PedidoCompraController::class, 'recibirStock'])
        ->name('compras.recibir')->middleware('permission:compras.editar');

    Route::resource('proveedores', \App\Http\Controllers\ProveedorController::class)
        ->parameters(['proveedores' => 'proveedor'])
        ->middlewareFor(['index', 'show'], 'permission:proveedores.ver')
        ->middlewareFor(['create', 'store'], 'permission:proveedores.crear')
        ->middlewareFor(['edit', 'update'], 'permission:proveedores.editar')
        ->middlewareFor('destroy', 'permission:proveedores.eliminar');

    Route::resource('traslados', \App\Http\Controllers\TrasladoController::class)
        ->only(['index', 'create', 'store', 'show'])
        ->middlewareFor(['index', 'show'], 'permission:compras.ver')
        ->middlewareFor(['create', 'store'], 'permission:productos.editar');

    // ===== REPORTES =====
    Route::prefix('reportes')->name('reportes.')->middleware('permission:reportes.ver')->group(function () {
        Route::get('stock',    [\App\Http\Controllers\ReporteController::class, 'stock'])->name('stock');
        Route::get('ventas',   [\App\Http\Controllers\ReporteController::class, 'ventas'])->name('ventas');
        Route::get('compras',  [\App\Http\Controllers\ReporteController::class, 'compras'])->name('compras')
            ->middleware('permission:reportes.compras');
        Route::get('stock/pdf',  [\App\Http\Controllers\ReporteController::class, 'stockPdf'])->name('stock.pdf');
        Route::get('ventas/pdf', [\App\Http\Controllers\ReporteController::class, 'ventasPdf'])->name('ventas.pdf');
        Route::get('stock/excel',  [\App\Http\Controllers\ReporteController::class, 'stockExcel'])->name('stock.excel')
            ->middleware('permission:reportes.exportar');
        Route::get('ventas/excel', [\App\Http\Controllers\ReporteController::class, 'ventasExcel'])->name('ventas.excel')
            ->middleware('permission:reportes.exportar');
    });

    // ===== CONFIGURACIÓN =====
    Route::prefix('configuracion')->name('configuracion.')->middleware('permission:configuracion.ver')->group(function () {
        Route::get('/', [\App\Http\Controllers\ConfiguracionController::class, 'index'])->name('index');
        Route::post('empresa',  [\App\Http\Controllers\ConfiguracionController::class, 'guardarEmpresa'])->name('empresa')
            ->middleware('permission:configuracion.editar');
        Route::post('sistema', [\App\Http\Controllers\ConfiguracionController::class, 'guardarSistema'])->name('sistema')
            ->middleware('permission:configuracion.editar');
        Route::post('facturacion', [\App\Http\Controllers\ConfiguracionController::class, 'guardarFacturacion'])->name('facturacion')
            ->middleware('permission:configuracion.editar');
        Route::resource('terminos-pago', \App\Http\Controllers\TerminoPagoController::class)
            ->parameters(['terminos-pago' => 'terminoPago'])->names('configuracion.terminos-pago')
            ->middleware('permission:configuracion.editar');
        Route::resource('metodos-pago', \App\Http\Controllers\MetodoPagoController::class)
            ->parameters(['metodos-pago' => 'metodoPago'])->names('configuracion.metodos-pago')
            ->middleware('permission:configuracion.editar');
    });

    // ===== EMPRESAS Y SUCURSALES (solo super-admin) =====
    Route::resource('empresas', \App\Http\Controllers\EmpresaController::class)
        ->middleware('role:admin');
    Route::post('empresas/{empresa}/sucursales', [\App\Http\Controllers\SucursalController::class, 'store'])
        ->name('empresas.sucursales.store')->middleware('role:admin');
    Route::patch('empresas/{empresa}/sucursales/{sucursal}', [\App\Http\Controllers\SucursalController::class, 'update'])
        ->name('empresas.sucursales.update')->middleware('role:admin');
    Route::delete('empresas/{empresa}/sucursales/{sucursal}', [\App\Http\Controllers\SucursalController::class, 'destroy'])
        ->name('empresas.sucursales.destroy')->middleware('role:admin');

    // ===== GRUPOS DE ACCESO (solo admin) =====
    Route::resource('grupos', \App\Http\Controllers\GrupoController::class)
        ->middleware('role:admin');
    Route::post('grupos/{grupo}/permisos', [\App\Http\Controllers\GrupoController::class, 'updatePermisos'])
        ->name('grupos.permisos')->middleware('role:admin');

    // ===== USUARIOS (solo admin) =====
    Route::resource('usuarios', \App\Http\Controllers\UsuarioController::class)
        ->middleware('role:admin');
    Route::post('usuarios/{usuario}/cambiar-contrasena', [\App\Http\Controllers\UsuarioController::class, 'cambiarContrasena'])
        ->name('usuarios.cambiar-contrasena')->middleware('role:admin');

    // ===== AUDITORÍA (solo admin) =====
    Route::get('auditoria', [\App\Http\Controllers\AuditoriaController::class, 'index'])
        ->name('auditoria.index')->middleware('role:admin');
});
