<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Descuento global en cabecera de documento (% sobre el subtotal total)
        foreach (['facturas', 'pedidos_venta', 'presupuestos'] as $tabla) {
            if (!Schema::hasColumn($tabla, 'descuento_global')) {
                Schema::table($tabla, function (Blueprint $table) use ($tabla) {
                    $after = $tabla === 'facturas' ? 'condicion_venta' : 'total';
                    $table->decimal('descuento_global', 5, 2)->default(0)->after($after);
                    $table->decimal('monto_descuento', 12, 2)->default(0)->after('descuento_global');
                });
            }
        }
    }

    public function down(): void
    {
        foreach (['facturas', 'pedidos_venta', 'presupuestos'] as $tabla) {
            Schema::table($tabla, function (Blueprint $table) {
                $table->dropColumn(['descuento_global', 'monto_descuento']);
            });
        }
    }
};
