<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->decimal('limite_credito', 12, 2)->nullable()->after('tipo_precio');
        });

        Schema::table('presupuestos', function (Blueprint $table) {
            $table->string('etapa', 40)->default('prospecto')->after('estado'); // catalogo_valores: 'presupuestos.etapa'
        });
    }

    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn('limite_credito');
        });
        Schema::table('presupuestos', function (Blueprint $table) {
            $table->dropColumn('etapa');
        });
    }
};
