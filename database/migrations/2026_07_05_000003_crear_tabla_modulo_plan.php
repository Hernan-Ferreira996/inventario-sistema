<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('modulo_plan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->constrained('planes')->cascadeOnDelete();
            $table->foreignId('modulo_id')->constrained('modulos')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['plan_id', 'modulo_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('modulo_plan');
    }
};
