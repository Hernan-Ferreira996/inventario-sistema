<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('etiquetas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->string('nombre', 60);
            $table->string('color', 20)->default('#94a3b8');
            $table->timestamps();

            $table->unique(['empresa_id', 'nombre'], 'uk_etiqueta_empresa');
        });

        Schema::create('etiquetables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('etiqueta_id')->constrained('etiquetas')->cascadeOnDelete();
            $table->morphs('etiquetable'); // etiquetable_type + etiquetable_id
            $table->timestamps();

            $table->unique(['etiqueta_id', 'etiquetable_type', 'etiquetable_id'], 'uk_etiquetable');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('etiquetables');
        Schema::dropIfExists('etiquetas');
    }
};
