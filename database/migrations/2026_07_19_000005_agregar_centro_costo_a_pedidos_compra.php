<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pedidos_compra', function (Blueprint $table) {
            $table->foreignId('centro_costo_id')->nullable()->after('proveedor_id')->constrained('centros_costo')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pedidos_compra', function (Blueprint $table) {
            $table->dropConstrainedForeignId('centro_costo_id');
        });
    }
};
