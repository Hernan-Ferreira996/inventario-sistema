<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('asientos_contables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->foreignId('sucursal_id')->nullable()->constrained('sucursales')->nullOnDelete();
            $table->foreignId('usuario_id')->constrained('users')->cascadeOnDelete();
            $table->string('numero', 30); // vía SecuenciaDocumento, tipo_documento='asientos_contables'
            $table->date('fecha');
            $table->string('concepto', 255);
            $table->string('origen', 30)->default('manual'); // 'manual','factura','pago','nota_credito','compra'
            $table->nullableMorphs('origen_documento', 'idx_asiento_origen'); // origen_documento_type/_id
            $table->timestamps();

            $table->unique(['empresa_id', 'numero'], 'uk_asiento_numero');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asientos_contables');
    }
};
