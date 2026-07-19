<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('notas_remision', function (Blueprint $table) {
            $table->boolean('afecta_stock')->default(true)->after('motivo');
        });
    }

    public function down(): void
    {
        Schema::table('notas_remision', function (Blueprint $table) {
            $table->dropColumn('afecta_stock');
        });
    }
};
