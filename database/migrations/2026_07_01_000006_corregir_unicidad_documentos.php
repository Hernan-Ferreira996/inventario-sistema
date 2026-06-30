<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Eliminar las restricciones UNIQUE antiguas de una sola columna
        // (impedían que el mismo número de documento existiera en distintas empresas)
        Schema::table('facturas', function (Blueprint $table) {
            $table->dropUnique('facturas_numero_factura_unique');
        });

        Schema::table('pedidos_venta', function (Blueprint $table) {
            $table->dropUnique('pedidos_venta_numero_referencia_unique');
            $table->unique(['empresa_id', 'sucursal_id', 'numero_referencia'], 'uk_negocio_pedidos_venta');
        });

        Schema::table('pedidos_compra', function (Blueprint $table) {
            $table->dropUnique('pedidos_compra_numero_referencia_unique');
            $table->unique(['empresa_id', 'sucursal_id', 'numero_referencia'], 'uk_negocio_pedidos_compra');
        });

        Schema::table('presupuestos', function (Blueprint $table) {
            $table->dropUnique('presupuestos_numero_documento_unique');
        });
    }

    public function down(): void
    {
        Schema::table('facturas', function (Blueprint $table) {
            $table->unique('numero_factura', 'facturas_numero_factura_unique');
        });
        Schema::table('pedidos_venta', function (Blueprint $table) {
            $table->dropUnique('uk_negocio_pedidos_venta');
            $table->unique('numero_referencia', 'pedidos_venta_numero_referencia_unique');
        });
        Schema::table('pedidos_compra', function (Blueprint $table) {
            $table->dropUnique('uk_negocio_pedidos_compra');
            $table->unique('numero_referencia', 'pedidos_compra_numero_referencia_unique');
        });
        Schema::table('presupuestos', function (Blueprint $table) {
            $table->unique('numero_documento', 'presupuestos_numero_documento_unique');
        });
    }
};
