<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cuentas_contables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->foreignId('cuenta_padre_id')->nullable()->constrained('cuentas_contables')->nullOnDelete();
            $table->string('codigo', 20);
            $table->string('nombre', 150);
            $table->string('tipo', 20); // catalogo_valores: 'cuentas_contables.tipo' (activo/pasivo/patrimonio/ingreso/gasto)
            $table->enum('naturaleza', ['deudora', 'acreedora']); // lado que aumenta el saldo normal de la cuenta
            $table->boolean('imputable')->default(true); // false = solo agrupa (cuenta de título), no recibe movimientos directos
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->unique(['empresa_id', 'codigo'], 'uk_cuenta_codigo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cuentas_contables');
    }
};
