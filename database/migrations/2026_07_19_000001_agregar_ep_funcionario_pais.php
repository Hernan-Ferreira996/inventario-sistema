<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->boolean('expuesto_publicamente')->default(false)->after('ruc_nit');
            $table->boolean('funcionario')->default(false)->after('expuesto_publicamente');
        });

        Schema::table('proveedores', function (Blueprint $table) {
            $table->boolean('expuesto_publicamente')->default(false)->after('ruc_nit');
            $table->boolean('funcionario')->default(false)->after('expuesto_publicamente');
            $table->string('pais', 60)->default('Paraguay')->after('funcionario');
        });
    }

    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn(['expuesto_publicamente', 'funcionario']);
        });

        Schema::table('proveedores', function (Blueprint $table) {
            $table->dropColumn(['expuesto_publicamente', 'funcionario', 'pais']);
        });
    }
};
