<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('envios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained('pedidos_venta')->cascadeOnDelete();
            $table->string('numero_envio', 50)->unique();
            $table->date('fecha_empaque');
            $table->date('fecha_entrega')->nullable();
            $table->text('comentarios')->nullable();
            $table->enum('estado', ['preparando', 'enviado', 'entregado', 'devuelto'])->default('preparando');
            $table->timestamps();
        });

        Schema::create('detalle_envios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('envio_id')->constrained('envios')->cascadeOnDelete();
            $table->foreignId('producto_id')->constrained('productos')->cascadeOnDelete();
            $table->decimal('cantidad', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalle_envios');
        Schema::dropIfExists('envios');
    }
};
