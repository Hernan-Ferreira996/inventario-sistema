<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('campos_personalizados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->string('entidad', 40); // 'cliente', 'proveedor', 'producto' (extensible a futuro)
            $table->string('nombre', 60); // clave interna, ej. 'fecha_nacimiento'
            $table->string('etiqueta', 100); // texto mostrado en el formulario
            $table->enum('tipo', ['texto', 'numero', 'fecha', 'booleano', 'select']);
            $table->json('opciones')->nullable(); // valores posibles cuando tipo='select'
            $table->boolean('requerido')->default(false);
            $table->integer('orden')->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->unique(['empresa_id', 'entidad', 'nombre'], 'uk_campo_personalizado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campos_personalizados');
    }
};
