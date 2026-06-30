<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('traslados_stock', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('ubicacion_origen_id')->constrained('ubicaciones')->cascadeOnDelete();
            $table->foreignId('ubicacion_destino_id')->constrained('ubicaciones')->cascadeOnDelete();
            $table->string('referencia', 100)->nullable();
            $table->text('notas')->nullable();
            $table->date('fecha_traslado');
            $table->timestamps();
        });

        Schema::create('detalle_traslados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('traslado_id')->constrained('traslados_stock')->cascadeOnDelete();
            $table->foreignId('producto_id')->constrained('productos')->cascadeOnDelete();
            $table->decimal('cantidad', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalle_traslados');
        Schema::dropIfExists('traslados_stock');
    }
};
