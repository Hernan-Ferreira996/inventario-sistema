<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('facturas', function (Blueprint $table) {
            $table->date('fecha_vencimiento')->nullable()->after('fecha_factura');
        });

        // Backfill: facturas a crédito existentes ya emitidas sin este campo
        // reciben el mismo vencimiento a 30 días que aplicará de acá en más.
        DB::table('facturas')
            ->where('condicion_venta', 'credito')
            ->whereNull('fecha_vencimiento')
            ->update([
                'fecha_vencimiento' => DB::raw("DATE_ADD(fecha_factura, INTERVAL 30 DAY)"),
            ]);
    }

    public function down(): void
    {
        Schema::table('facturas', function (Blueprint $table) {
            $table->dropColumn('fecha_vencimiento');
        });
    }
};
