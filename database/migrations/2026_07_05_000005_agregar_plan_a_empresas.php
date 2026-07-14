<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->foreignId('plan_id')->nullable()->after('id')
                ->constrained('planes')->nullOnDelete();
            $table->date('fecha_vencimiento_licencia')->nullable()->after('plan_id');
            $table->integer('max_usuarios')->nullable()->after('fecha_vencimiento_licencia');
        });
    }

    public function down(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->dropForeign(['plan_id']);
            $table->dropColumn(['plan_id', 'fecha_vencimiento_licencia', 'max_usuarios']);
        });
    }
};
