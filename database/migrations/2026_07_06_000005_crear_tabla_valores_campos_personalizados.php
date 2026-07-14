<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('valores_campos_personalizados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campo_id')->constrained('campos_personalizados')->cascadeOnDelete();
            $table->morphs('valorable'); // valorable_type + valorable_id (Cliente, Proveedor, ...)
            $table->text('valor')->nullable();
            $table->timestamps();

            $table->unique(['campo_id', 'valorable_type', 'valorable_id'], 'uk_valor_campo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('valores_campos_personalizados');
    }
};
