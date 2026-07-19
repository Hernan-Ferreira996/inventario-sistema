<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->foreignId('ciudad_id')->nullable()->after('direccion')->constrained('ciudades')->nullOnDelete();
        });
        Schema::table('proveedores', function (Blueprint $table) {
            $table->foreignId('ciudad_id')->nullable()->after('direccion')->constrained('ciudades')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropConstrainedForeignId('ciudad_id');
        });
        Schema::table('proveedores', function (Blueprint $table) {
            $table->dropConstrainedForeignId('ciudad_id');
        });
    }
};
