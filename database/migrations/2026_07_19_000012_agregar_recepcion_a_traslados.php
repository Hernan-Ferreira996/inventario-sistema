<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('traslados_stock', function (Blueprint $table) {
            $table->string('estado', 20)->default('en_transito')->after('fecha_traslado'); // catalogo_valores: 'traslados.estado'
            $table->date('fecha_recepcion')->nullable()->after('estado');
            $table->foreignId('usuario_recepcion_id')->nullable()->after('fecha_recepcion')->constrained('users')->nullOnDelete();
        });

        Schema::table('detalle_traslados', function (Blueprint $table) {
            $table->decimal('cantidad_recibida', 12, 2)->nullable()->after('cantidad');
        });

        // Los traslados creados antes de este cambio ya movieron el stock de punta a
        // punta al crearse (origen y destino a la vez). Marcarlos "recibido" desde ya
        // evita que alguien los "confirme" después y duplique la entrada en destino.
        DB::statement("UPDATE traslados_stock SET estado = 'recibido', fecha_recepcion = fecha_traslado");
        DB::statement("UPDATE detalle_traslados SET cantidad_recibida = cantidad");
    }

    public function down(): void
    {
        Schema::table('detalle_traslados', function (Blueprint $table) {
            $table->dropColumn('cantidad_recibida');
        });
        Schema::table('traslados_stock', function (Blueprint $table) {
            $table->dropColumn('estado');
            $table->dropColumn('fecha_recepcion');
            $table->dropConstrainedForeignId('usuario_recepcion_id');
        });
    }
};
