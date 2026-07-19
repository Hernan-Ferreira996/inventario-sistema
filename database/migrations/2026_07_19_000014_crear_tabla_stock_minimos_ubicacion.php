<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('stock_minimos_ubicacion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained('productos')->cascadeOnDelete();
            $table->foreignId('ubicacion_id')->constrained('ubicaciones')->cascadeOnDelete();
            $table->decimal('cantidad_minima', 12, 2);
            $table->timestamps();

            $table->unique(['producto_id', 'ubicacion_id'], 'uk_stock_minimo_producto_ubicacion');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_minimos_ubicacion');
    }
};
