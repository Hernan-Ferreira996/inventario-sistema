<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 200);
            $table->string('nombre_fantasia', 200)->nullable();
            $table->string('ruc', 20)->nullable();
            $table->string('dv', 2)->nullable();
            $table->string('telefono', 30)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('web', 150)->nullable();
            $table->string('direccion', 255)->nullable();
            $table->string('ciudad', 100)->nullable();
            $table->string('pais', 100)->default('Paraguay');
            $table->string('moneda', 10)->default('PYG');
            $table->string('simbolo', 6)->default('Gs.');
            // Facturación electrónica
            $table->string('fact_timbrado', 20)->nullable();
            $table->date('fact_fecha_inicio_vigencia')->nullable();
            $table->string('fact_establecimiento', 3)->default('001');
            $table->string('fact_punto_expedicion', 3)->default('001');
            $table->enum('fact_modo', ['local', 'electronico'])->default('local');
            // Config sistema
            $table->string('timezone', 50)->default('America/Asuncion');
            $table->tinyInteger('decimales')->default(0);
            $table->integer('stock_minimo')->default(5);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
