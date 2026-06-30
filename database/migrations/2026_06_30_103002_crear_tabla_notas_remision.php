<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('notas_remision', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->nullable()->constrained('pedidos_venta')->nullOnDelete();
            $table->foreignId('envio_id')->nullable()->constrained('envios')->nullOnDelete();
            $table->foreignId('usuario_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('ubicacion_origen_id')->nullable()->constrained('ubicaciones')->nullOnDelete();
            $table->string('numero_documento', 20);
            $table->string('timbrado', 20)->nullable();
            $table->string('establecimiento', 3)->default('001');
            $table->string('punto_expedicion', 3)->default('001');
            $table->string('cdc', 44)->nullable();
            $table->enum('modo', ['local', 'electronico'])->default('local');
            $table->date('fecha_emision');
            $table->enum('motivo', ['venta', 'consignacion', 'traslado', 'devolucion', 'otro'])->default('venta');
            $table->string('direccion_destino')->nullable();
            $table->string('transportista')->nullable();
            $table->string('vehiculo_placa', 20)->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });

        Schema::create('detalle_notas_remision', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nota_remision_id')->constrained('notas_remision')->cascadeOnDelete();
            $table->foreignId('producto_id')->constrained('productos')->cascadeOnDelete();
            $table->decimal('cantidad', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalle_notas_remision');
        Schema::dropIfExists('notas_remision');
    }
};
