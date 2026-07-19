<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cajas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->nullable()->constrained('empresas')->cascadeOnDelete();
            $table->string('nombre', 100);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        Schema::create('rendiciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->nullable()->constrained('empresas')->cascadeOnDelete();
            $table->foreignId('caja_id')->constrained('cajas')->cascadeOnDelete();
            $table->foreignId('cobrador_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('usuario_id')->constrained('users')->cascadeOnDelete();
            $table->date('fecha');
            $table->decimal('monto_total', 14, 2)->default(0);
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });

        Schema::create('cierres_caja', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->nullable()->constrained('empresas')->cascadeOnDelete();
            $table->foreignId('caja_id')->constrained('cajas')->cascadeOnDelete();
            $table->foreignId('usuario_id')->constrained('users')->cascadeOnDelete();
            $table->date('fecha');
            $table->decimal('saldo_inicial', 14, 2)->default(0);
            $table->decimal('total_cobrado', 14, 2)->default(0);
            $table->decimal('saldo_final', 14, 2)->default(0);
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });

        Schema::table('pagos', function (Blueprint $table) {
            $table->foreignId('caja_id')->nullable()->after('metodo_pago_id')->constrained('cajas')->nullOnDelete();
            $table->foreignId('cobrador_id')->nullable()->after('caja_id')->constrained('users')->nullOnDelete();
            $table->string('numero_recibo', 30)->nullable()->after('cobrador_id');
            $table->foreignId('rendicion_id')->nullable()->after('numero_recibo')->constrained('rendiciones')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pagos', function (Blueprint $table) {
            $table->dropConstrainedForeignId('caja_id');
            $table->dropConstrainedForeignId('cobrador_id');
            $table->dropColumn('numero_recibo');
            $table->dropConstrainedForeignId('rendicion_id');
        });
        Schema::dropIfExists('cierres_caja');
        Schema::dropIfExists('rendiciones');
        Schema::dropIfExists('cajas');
    }
};
