<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pedidos_compra', function (Blueprint $table) {
            $table->string('tipo', 20)->default('local')->after('proveedor_id'); // catalogo_valores: 'pedidos_compra.tipo'
        });
    }

    public function down(): void
    {
        Schema::table('pedidos_compra', function (Blueprint $table) {
            $table->dropColumn('tipo');
        });
    }
};
