<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('envios', function (Blueprint $table) {
            $table->string('transportista', 150)->nullable()->after('estado');
            $table->string('chofer', 150)->nullable()->after('transportista');
            $table->string('vehiculo_placa', 20)->nullable()->after('chofer');
        });
    }

    public function down(): void
    {
        Schema::table('envios', function (Blueprint $table) {
            $table->dropColumn(['transportista', 'chofer', 'vehiculo_placa']);
        });
    }
};
