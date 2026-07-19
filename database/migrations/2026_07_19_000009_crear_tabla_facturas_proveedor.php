<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('facturas_proveedor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->nullable()->constrained('empresas')->cascadeOnDelete();
            $table->foreignId('sucursal_id')->nullable()->constrained('sucursales')->nullOnDelete();
            $table->foreignId('proveedor_id')->constrained('proveedores')->cascadeOnDelete();
            $table->foreignId('centro_costo_id')->nullable()->constrained('centros_costo')->nullOnDelete();
            $table->foreignId('usuario_id')->constrained('users')->cascadeOnDelete();

            $table->string('numero_referencia', 30); // numeración interna, FP-000001
            $table->string('numero_factura_proveedor', 30); // el número impreso en la factura del proveedor
            $table->string('timbrado_proveedor', 20)->nullable();
            $table->string('ruc_proveedor', 30)->nullable(); // snapshot al momento de cargar el documento

            $table->date('fecha_emision');
            $table->date('fecha_vencimiento')->nullable();

            $table->decimal('subtotal', 14, 2)->default(0);
            $table->decimal('iva_total', 14, 2)->default(0);
            $table->decimal('total', 14, 2)->default(0);
            $table->decimal('monto_pagado', 14, 2)->default(0);

            $table->boolean('retiene_iva')->default(false);
            $table->string('retencion_timbrado', 20)->nullable();
            $table->string('retencion_numero', 20)->nullable();
            $table->decimal('retencion_porcentaje', 5, 2)->nullable();
            $table->decimal('retencion_monto', 14, 2)->default(0);

            $table->string('estado', 30)->default('pendiente'); // catalogo_valores: 'facturas_proveedor.estado'
            $table->text('observaciones')->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->unique(['empresa_id', 'proveedor_id', 'numero_factura_proveedor'], 'uk_factura_proveedor_numero');
        });

        Schema::create('detalle_facturas_proveedor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('factura_proveedor_id')->constrained('facturas_proveedor')->cascadeOnDelete();
            $table->foreignId('centro_costo_id')->nullable()->constrained('centros_costo')->nullOnDelete();
            $table->string('concepto', 255);
            $table->decimal('cantidad', 12, 2)->default(1);
            $table->decimal('precio_unitario', 14, 2);
            $table->decimal('subtotal', 14, 2);
            $table->timestamps();
        });

        Schema::create('cuotas_factura_proveedor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('factura_proveedor_id')->constrained('facturas_proveedor')->cascadeOnDelete();
            $table->unsignedInteger('numero_cuota');
            $table->date('fecha_vencimiento');
            $table->decimal('monto', 14, 2);
            $table->boolean('pagada')->default(false);
            $table->date('fecha_pago')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cuotas_factura_proveedor');
        Schema::dropIfExists('detalle_facturas_proveedor');
        Schema::dropIfExists('facturas_proveedor');
    }
};
