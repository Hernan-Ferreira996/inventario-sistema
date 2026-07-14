<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Los enum() de MySQL/MariaDB bloquean valores nuevos a nivel de esquema.
        // Se convierten a VARCHAR para que catalogo_valores pueda ofrecer códigos
        // adicionales sin requerir una migración por cada valor nuevo.
        DB::statement("ALTER TABLE pedidos_venta MODIFY estado VARCHAR(60) NOT NULL DEFAULT 'activo'");
        DB::statement("ALTER TABLE pedidos_venta MODIFY estado_factura VARCHAR(60) NOT NULL DEFAULT 'pendiente'");
        DB::statement("ALTER TABLE facturas MODIFY estado VARCHAR(60) NOT NULL DEFAULT 'pendiente'");
        DB::statement("ALTER TABLE envios MODIFY estado VARCHAR(60) NOT NULL DEFAULT 'preparando'");
        DB::statement("ALTER TABLE pedidos_compra MODIFY estado VARCHAR(60) NOT NULL DEFAULT 'pendiente'");
        DB::statement("ALTER TABLE notas_credito MODIFY motivo VARCHAR(60) NOT NULL DEFAULT 'devolucion_parcial'");
        DB::statement("ALTER TABLE notas_remision MODIFY motivo VARCHAR(60) NOT NULL DEFAULT 'venta'");
        DB::statement("ALTER TABLE presupuestos MODIFY estado VARCHAR(60) NOT NULL DEFAULT 'pendiente'");
        DB::statement("ALTER TABLE clientes MODIFY tipo_precio VARCHAR(60) NOT NULL DEFAULT 'minorista'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE pedidos_venta MODIFY estado ENUM('activo','cancelado','completado') NOT NULL DEFAULT 'activo'");
        DB::statement("ALTER TABLE pedidos_venta MODIFY estado_factura ENUM('pendiente','parcial','completado') NOT NULL DEFAULT 'pendiente'");
        DB::statement("ALTER TABLE facturas MODIFY estado ENUM('pendiente','parcial','pagada','anulada') NOT NULL DEFAULT 'pendiente'");
        DB::statement("ALTER TABLE envios MODIFY estado ENUM('preparando','enviado','entregado','devuelto') NOT NULL DEFAULT 'preparando'");
        DB::statement("ALTER TABLE pedidos_compra MODIFY estado ENUM('pendiente','parcial','completado','cancelado') NOT NULL DEFAULT 'pendiente'");
        DB::statement("ALTER TABLE notas_credito MODIFY motivo ENUM('devolucion_total','devolucion_parcial','descuento','anulacion','otro') NOT NULL DEFAULT 'devolucion_parcial'");
        DB::statement("ALTER TABLE notas_remision MODIFY motivo ENUM('venta','consignacion','traslado','devolucion','otro') NOT NULL DEFAULT 'venta'");
        DB::statement("ALTER TABLE presupuestos MODIFY estado ENUM('pendiente','aprobado','rechazado','vencido','convertido') NOT NULL DEFAULT 'pendiente'");
        DB::statement("ALTER TABLE clientes MODIFY tipo_precio ENUM('minorista','mayorista') NOT NULL DEFAULT 'minorista'");
    }
};
