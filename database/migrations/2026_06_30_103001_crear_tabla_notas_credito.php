<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('notas_credito', function (Blueprint $table) {
            $table->id();
            $table->foreignId('factura_id')->constrained('facturas')->cascadeOnDelete();
            $table->foreignId('usuario_id')->constrained('users')->cascadeOnDelete();
            $table->string('numero_documento', 20);
            $table->string('timbrado', 20)->nullable();
            $table->string('establecimiento', 3)->default('001');
            $table->string('punto_expedicion', 3)->default('001');
            $table->string('cdc', 44)->nullable();
            $table->enum('modo', ['local', 'electronico'])->default('local');
            $table->date('fecha_emision');
            $table->enum('motivo', ['devolucion_total', 'devolucion_parcial', 'descuento', 'anulacion', 'otro'])->default('devolucion_parcial');
            $table->text('descripcion_motivo')->nullable();
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('impuesto_total', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('detalle_notas_credito', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nota_credito_id')->constrained('notas_credito')->cascadeOnDelete();
            $table->foreignId('producto_id')->constrained('productos')->cascadeOnDelete();
            $table->decimal('cantidad', 12, 2);
            $table->decimal('precio_unitario', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalle_notas_credito');
        Schema::dropIfExists('notas_credito');
    }
};
