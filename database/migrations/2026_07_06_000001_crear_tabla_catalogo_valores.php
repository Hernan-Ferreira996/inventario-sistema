<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('catalogo_valores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->nullable()->constrained('empresas')->cascadeOnDelete();
            $table->string('grupo', 60);       // 'pedidos_venta.estado', 'facturas.estado', 'notas_credito.motivo', ...
            $table->string('codigo', 60);      // valor real guardado en la columna (ej. 'pendiente')
            $table->string('etiqueta', 100);   // texto mostrado (ej. 'Pendiente')
            $table->string('color', 20)->nullable();       // hex fondo del badge, ej '#fbbf24'
            $table->string('color_texto', 20)->nullable(); // hex texto del badge, ej '#000000'
            $table->integer('orden')->default(0);
            $table->boolean('activo')->default(true);
            $table->boolean('protegido')->default(false); // valores del sistema, no se pueden eliminar
            $table->timestamps();

            $table->unique(['empresa_id', 'grupo', 'codigo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('catalogo_valores');
    }
};
