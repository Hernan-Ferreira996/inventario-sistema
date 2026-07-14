<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('documentos_adjuntos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->morphs('adjuntable'); // adjuntable_type + adjuntable_id (Cliente, Proveedor)
            $table->foreignId('usuario_id')->constrained('users')->cascadeOnDelete();
            $table->string('nombre_archivo', 255);
            $table->string('ruta', 255); // path en disco 'local' (privado), no accesible por URL directa
            $table->string('tipo_mime', 100)->nullable();
            $table->unsignedBigInteger('tamano')->nullable(); // bytes
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documentos_adjuntos');
    }
};
