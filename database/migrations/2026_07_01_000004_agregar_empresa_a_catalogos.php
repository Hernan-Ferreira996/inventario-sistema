<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Tablas de catálogo: cada empresa tiene los propios
        $catalogos = ['categorias', 'unidades', 'impuestos', 'terminos_pago', 'metodos_pago'];
        foreach ($catalogos as $tabla) {
            Schema::table($tabla, function (Blueprint $table) {
                $table->foreignId('empresa_id')->nullable()->after('id')
                    ->constrained('empresas')->cascadeOnDelete();
            });
        }

        // Ubicaciones ahora son Depósitos y pertenecen a una Sucursal
        Schema::table('ubicaciones', function (Blueprint $table) {
            $table->foreignId('empresa_id')->nullable()->after('id')
                ->constrained('empresas')->cascadeOnDelete();
            $table->foreignId('sucursal_id')->nullable()->after('empresa_id')
                ->constrained('sucursales')->cascadeOnDelete();
        });

        // Catálogos comerciales
        foreach (['clientes', 'proveedores', 'productos'] as $tabla) {
            Schema::table($tabla, function (Blueprint $table) {
                $table->foreignId('empresa_id')->nullable()->after('id')
                    ->constrained('empresas')->cascadeOnDelete();
            });
        }
    }

    public function down(): void
    {
        $tablas = ['categorias','unidades','impuestos','terminos_pago','metodos_pago',
                   'clientes','proveedores','productos'];
        foreach ($tablas as $tabla) {
            Schema::table($tabla, function (Blueprint $table) use ($tabla) {
                try { $table->dropForeign([$tabla === 'productos' ? 'empresa_id' : 'empresa_id']); } catch (\Exception $e) {}
                try { $table->dropColumn('empresa_id'); } catch (\Exception $e) {}
            });
        }
        Schema::table('ubicaciones', function (Blueprint $table) {
            try { $table->dropForeign(['sucursal_id']); } catch (\Exception $e) {}
            try { $table->dropColumn(['empresa_id', 'sucursal_id']); } catch (\Exception $e) {}
        });
    }
};
