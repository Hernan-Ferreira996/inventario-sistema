<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 'envios' nunca recibió empresa_id pese a que el modelo Envio ya usa
        // el trait PerteneceAEmpresa (gap de la migración 2026_07_01_000005) —
        // se corrige acá junto con sucursal_id.
        Schema::table('envios', function (Blueprint $table) {
            $table->foreignId('empresa_id')->nullable()->after('id')
                ->constrained('empresas')->cascadeOnDelete();
            $table->foreignId('sucursal_id')->nullable()->after('empresa_id')
                ->constrained('sucursales')->nullOnDelete();
        });

        Schema::table('movimientos_stock', function (Blueprint $table) {
            $table->foreignId('sucursal_id')->nullable()->after('empresa_id')
                ->constrained('sucursales')->nullOnDelete();
        });

        Schema::table('traslados_stock', function (Blueprint $table) {
            $table->foreignId('sucursal_id')->nullable()->after('empresa_id')
                ->constrained('sucursales')->nullOnDelete();
        });

        Schema::table('pagos', function (Blueprint $table) {
            $table->foreignId('sucursal_id')->nullable()->after('empresa_id')
                ->constrained('sucursales')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('envios', function (Blueprint $table) {
            $table->dropForeign(['empresa_id']);
            $table->dropForeign(['sucursal_id']);
            $table->dropColumn(['empresa_id', 'sucursal_id']);
        });
        Schema::table('movimientos_stock', function (Blueprint $table) {
            $table->dropForeign(['sucursal_id']);
            $table->dropColumn('sucursal_id');
        });
        Schema::table('traslados_stock', function (Blueprint $table) {
            $table->dropForeign(['sucursal_id']);
            $table->dropColumn('sucursal_id');
        });
        Schema::table('pagos', function (Blueprint $table) {
            $table->dropForeign(['sucursal_id']);
            $table->dropColumn('sucursal_id');
        });
    }
};
