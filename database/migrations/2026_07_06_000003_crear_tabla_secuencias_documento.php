<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('secuencias_documento', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->foreignId('sucursal_id')->constrained('sucursales')->cascadeOnDelete();
            $table->string('tipo_documento', 40); // 'pedidos_venta', 'presupuestos', 'facturas', 'notas_credito', 'notas_remision', 'pedidos_compra', 'envios'
            $table->string('prefijo', 20)->nullable();
            $table->unsignedInteger('longitud')->default(6);
            $table->unsignedBigInteger('proximo_numero')->default(1);
            $table->enum('reinicio', ['nunca', 'anual'])->default('nunca');
            $table->unsignedSmallInteger('ultimo_anio_reinicio')->nullable();
            $table->timestamps();

            $table->unique(['empresa_id', 'sucursal_id', 'tipo_documento'], 'uk_secuencia_doc');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('secuencias_documento');
    }
};
