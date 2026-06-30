<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // ── PEDIDOS DE VENTA ──────────────────────────────────────────────
        Schema::table('pedidos_venta', function (Blueprint $table) {
            $table->foreignId('empresa_id')->nullable()->after('id')->constrained('empresas')->cascadeOnDelete();
            $table->foreignId('sucursal_id')->nullable()->after('empresa_id')->constrained('sucursales')->nullOnDelete();
        });

        // ── PRESUPUESTOS ──────────────────────────────────────────────────
        Schema::table('presupuestos', function (Blueprint $table) {
            $table->foreignId('empresa_id')->nullable()->after('id')->constrained('empresas')->cascadeOnDelete();
            $table->foreignId('sucursal_id')->nullable()->after('empresa_id')->constrained('sucursales')->nullOnDelete();
            // PK de negocio compuesta: empresa + sucursal + numero
            $table->unique(['empresa_id', 'sucursal_id', 'numero_documento'], 'pk_negocio_presupuestos');
        });

        // ── PEDIDOS DE COMPRA ─────────────────────────────────────────────
        Schema::table('pedidos_compra', function (Blueprint $table) {
            $table->foreignId('empresa_id')->nullable()->after('id')->constrained('empresas')->cascadeOnDelete();
            $table->foreignId('sucursal_id')->nullable()->after('empresa_id')->constrained('sucursales')->nullOnDelete();
        });

        // ── FACTURAS ──────────────────────────────────────────────────────
        // Clave de negocio: empresa + establecimiento + punto_expedicion + numero
        // El mismo numero 001-001-0000001 puede existir para distintas empresas
        Schema::table('facturas', function (Blueprint $table) {
            $table->foreignId('empresa_id')->nullable()->after('id')->constrained('empresas')->cascadeOnDelete();
            $table->foreignId('sucursal_id')->nullable()->after('empresa_id')->constrained('sucursales')->nullOnDelete();
            // Clave única de negocio (equivalente a PK compuesta para integridad)
            $table->unique(
                ['empresa_id', 'establecimiento', 'punto_expedicion', 'numero_factura'],
                'uk_negocio_facturas'
            );
        });

        // ── NOTAS DE CRÉDITO ──────────────────────────────────────────────
        Schema::table('notas_credito', function (Blueprint $table) {
            $table->foreignId('empresa_id')->nullable()->after('id')->constrained('empresas')->cascadeOnDelete();
            $table->foreignId('sucursal_id')->nullable()->after('empresa_id')->constrained('sucursales')->nullOnDelete();
            $table->unique(
                ['empresa_id', 'establecimiento', 'punto_expedicion', 'numero_documento'],
                'uk_negocio_notas_credito'
            );
        });

        // ── NOTAS DE REMISIÓN ─────────────────────────────────────────────
        Schema::table('notas_remision', function (Blueprint $table) {
            $table->foreignId('empresa_id')->nullable()->after('id')->constrained('empresas')->cascadeOnDelete();
            $table->foreignId('sucursal_id')->nullable()->after('empresa_id')->constrained('sucursales')->nullOnDelete();
            $table->unique(
                ['empresa_id', 'establecimiento', 'punto_expedicion', 'numero_documento'],
                'uk_negocio_notas_remision'
            );
        });

        // ── MOVIMIENTOS DE STOCK ──────────────────────────────────────────
        Schema::table('movimientos_stock', function (Blueprint $table) {
            $table->foreignId('empresa_id')->nullable()->after('id')->constrained('empresas')->cascadeOnDelete();
        });

        // ── PAGOS ─────────────────────────────────────────────────────────
        Schema::table('pagos', function (Blueprint $table) {
            $table->foreignId('empresa_id')->nullable()->after('id')->constrained('empresas')->cascadeOnDelete();
        });

        // ── TRASLADOS DE STOCK ────────────────────────────────────────────
        Schema::table('traslados_stock', function (Blueprint $table) {
            $table->foreignId('empresa_id')->nullable()->after('id')->constrained('empresas')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        $tablas = [
            'pedidos_venta' => ['empresa_id', 'sucursal_id'],
            'presupuestos' => ['empresa_id', 'sucursal_id'],
            'pedidos_compra' => ['empresa_id', 'sucursal_id'],
            'facturas' => ['empresa_id', 'sucursal_id'],
            'notas_credito' => ['empresa_id', 'sucursal_id'],
            'notas_remision' => ['empresa_id', 'sucursal_id'],
            'movimientos_stock' => ['empresa_id'],
            'pagos' => ['empresa_id'],
            'traslados_stock' => ['empresa_id'],
        ];
        foreach ($tablas as $tabla => $cols) {
            Schema::table($tabla, function (Blueprint $table) use ($cols) {
                foreach ($cols as $col) {
                    try { $table->dropForeign([$col]); } catch (\Exception $e) {}
                }
                $table->dropColumn($cols);
            });
        }
    }
};
