<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pedidos_compra', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proveedor_id')->constrained('proveedores')->cascadeOnDelete();
            $table->foreignId('usuario_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('ubicacion_id')->nullable()->constrained('ubicaciones')->nullOnDelete();
            $table->string('numero_referencia', 30)->unique();
            $table->text('comentarios')->nullable();
            $table->date('fecha_pedido');
            $table->date('fecha_esperada')->nullable();
            $table->decimal('total', 12, 2)->default(0);
            $table->boolean('impuesto_incluido')->default(false);
            $table->enum('estado', ['pendiente', 'parcial', 'completado', 'cancelado'])->default('pendiente');
            $table->timestamps();
        });

        Schema::create('detalle_pedidos_compra', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_compra_id')->constrained('pedidos_compra')->cascadeOnDelete();
            $table->foreignId('producto_id')->constrained('productos')->cascadeOnDelete();
            $table->decimal('cantidad', 12, 2);
            $table->decimal('precio_unitario', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->decimal('cantidad_recibida', 12, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('recepciones_compra', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_compra_id')->constrained('pedidos_compra')->cascadeOnDelete();
            $table->foreignId('usuario_id')->constrained('users')->cascadeOnDelete();
            $table->date('fecha_recepcion');
            $table->string('numero_referencia', 50)->nullable();
            $table->text('notas')->nullable();
            $table->timestamps();
        });

        Schema::create('detalle_recepciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recepcion_id')->constrained('recepciones_compra')->cascadeOnDelete();
            $table->foreignId('producto_id')->constrained('productos')->cascadeOnDelete();
            $table->foreignId('ubicacion_id')->constrained('ubicaciones')->cascadeOnDelete();
            $table->decimal('cantidad', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalle_recepciones');
        Schema::dropIfExists('recepciones_compra');
        Schema::dropIfExists('detalle_pedidos_compra');
        Schema::dropIfExists('pedidos_compra');
    }
};
