# Inventario Pro

Sistema de inventario, gestión de pedidos y facturación electrónica para Paraguay, desarrollado con **Laravel 11** y **MariaDB/MySQL**.

## Tecnologías

- **Backend**: PHP 8.3 + Laravel 11
- **Base de datos**: MariaDB 10.4 / MySQL 8+
- **Frontend**: Bootstrap 5 + Bootstrap Icons + Chart.js
- **Auth**: Laravel Breeze + Spatie Permission (roles y permisos)
- **PDF**: barryvdh/laravel-dompdf
- **Excel**: maatwebsite/excel
- **Auditoría**: Spatie Activity Log

## Funcionalidades principales

- Multi-empresa con aislamiento total de datos (Global Scope automático)
- Jerarquía: Empresa → Sucursales → Depósitos
- Grupos de acceso dinámicos (estilo ScriptCase) con matriz de permisos por módulo
- Facturación electrónica modo local/demo (compatible con SIFEN Paraguay)
  - Factura, Nota de Crédito, Nota de Remisión con PDF en formato KuDE
  - Clave de negocio compuesta: empresa + establecimiento + punto + número
- Ciclo de venta completo: Presupuesto → Pedido → Factura → Pago
- Ciclo de compra: Pedido de Compra → Recepción → Stock
- Traslados de stock entre almacenes con trazabilidad
- Reportes con exportación a Excel y PDF
- Auditoría de cambios: quién hizo qué y cuándo
- Búsqueda y filtros en todos los módulos
- Notificaciones de stock bajo en tiempo real

## Requisitos

- PHP >= 8.2
- MariaDB >= 10.4 o MySQL >= 8.0
- Composer

## Instalación local

```bash
git clone https://github.com/TU_USUARIO/inventario-sistema.git
cd inventario-sistema
composer install
cp .env.example .env
php artisan key:generate
# Configurar .env con tus credenciales de base de datos
php artisan migrate --seed
php artisan serve
```

Credenciales por defecto: `admin@inventario.com` / `password`

## Módulos

| Módulo | Estado |
|---|---|
| Empresas, Sucursales, Depósitos | ✅ Completo |
| Grupos de Acceso + Permisos dinámicos | ✅ Completo |
| Usuarios con asignación de grupos | ✅ Completo |
| Presupuestos | ✅ Completo |
| Pedidos de Venta | ✅ Completo |
| Facturas (modo local + SIFEN ready) | ✅ Completo |
| Notas de Crédito | ✅ Completo |
| Notas de Remisión | ✅ Completo |
| Pagos con conciliación automática | ✅ Completo |
| Envíos / Despachos | ✅ Completo |
| Proveedores | ✅ Completo |
| Pedidos de Compra + Recepción | ✅ Completo |
| Traslados de Stock | ✅ Completo |
| Reportes (Excel + PDF) | ✅ Completo |
| Auditoría de actividad | ✅ Completo |
| Dashboard con KPIs y gráficos | ✅ Completo |

## Arquitectura destacada

- `EmpresaScope` — Global Scope que aísla todos los queries por empresa automáticamente
- `PerteneceAEmpresa` — Trait aplicado a 19 modelos, asigna `empresa_id` al crear registros
- Clave de negocio compuesta en documentos fiscales (UNIQUE constraint, no PK física)
- Módulo de permisos con detección automática de módulos nuevos

---

Desarrollado en Paraguay 🇵🇾 | Stack: Laravel 11 + Bootstrap 5 + MariaDB
