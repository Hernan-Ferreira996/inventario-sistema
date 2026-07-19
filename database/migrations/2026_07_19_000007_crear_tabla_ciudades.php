<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ciudades', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('departamento', 100)->nullable();
            $table->string('pais', 60)->default('Paraguay');
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->unique(['nombre', 'departamento', 'pais']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ciudades');
    }
};
