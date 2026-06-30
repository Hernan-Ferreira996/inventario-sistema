<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('facturas', function (Blueprint $table) {
            $table->string('timbrado', 20)->nullable()->after('numero_factura');
            $table->string('establecimiento', 3)->default('001')->after('timbrado');
            $table->string('punto_expedicion', 3)->default('001')->after('establecimiento');
            $table->string('cdc', 44)->nullable()->after('punto_expedicion');
            $table->enum('modo', ['local', 'electronico'])->default('local')->after('cdc');
            $table->string('tipo_documento_cliente', 5)->nullable()->after('modo');
            $table->string('numero_documento_cliente', 20)->nullable()->after('tipo_documento_cliente');
            $table->string('condicion_venta', 20)->default('contado')->after('numero_documento_cliente');
        });
    }

    public function down(): void
    {
        Schema::table('facturas', function (Blueprint $table) {
            $table->dropColumn([
                'timbrado', 'establecimiento', 'punto_expedicion', 'cdc', 'modo',
                'tipo_documento_cliente', 'numero_documento_cliente', 'condicion_venta',
            ]);
        });
    }
};
