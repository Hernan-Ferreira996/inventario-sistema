<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pedidos_venta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->cascadeOnDelete();
            $table->foreignId('usuario_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('ubicacion_id')->nullable()->constrained('ubicaciones')->nullOnDelete();
            $table->foreignId('termino_pago_id')->nullable()->constrained('terminos_pago')->nullOnDelete();
            $table->string('numero_referencia', 100)->unique();
            $table->string('referencia_cliente', 50)->nullable();
            $table->text('comentarios')->nullable();
            $table->date('fecha_pedido');
            $table->date('fecha_entrega')->nullable();
            $table->string('direccion_entrega', 200)->nullable();
            $table->string('telefono_contacto', 30)->nullable();
            $table->string('email_contacto', 100)->nullable();
            $table->decimal('total', 12, 2)->default(0);
            $table->decimal('monto_pagado', 12, 2)->default(0);
            $table->enum('estado_factura', ['pendiente', 'parcial', 'completado'])->default('pendiente');
            $table->enum('estado', ['activo', 'cancelado', 'completado'])->default('activo');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedidos_venta');
    }
};
