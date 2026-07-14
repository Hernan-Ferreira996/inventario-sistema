<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('movimientos_stock', function (Blueprint $table) {
            $table->nullableMorphs('origen_documento', 'idx_movstock_origen');
            $table->index(['producto_id', 'ubicacion_id'], 'idx_movstock_producto_ubicacion');
        });
    }

    public function down(): void
    {
        Schema::table('movimientos_stock', function (Blueprint $table) {
            $table->dropIndex('idx_movstock_producto_ubicacion');
            $table->dropMorphs('origen_documento', 'idx_movstock_origen');
        });
    }
};
