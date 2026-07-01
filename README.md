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

### 🏢 Administración
| Módulo | Descripción | Estado |
|--------|-------------|--------|
| Empresas | Multi-empresa con aislamiento total de datos | ✅ |
| Sucursales y Depósitos | Jerarquía Empresa → Sucursal → Depósito | ✅ |
| Usuarios | Creación y asignación de grupos de acceso | ✅ |
| Grupos y Permisos | Matriz de permisos dinámica por módulo (estilo ScriptCase) | ✅ |
| Auditoría | Registro de quién hizo qué y cuándo (Spatie Activity Log) | ✅ |

### 🛒 Ciclo de Ventas
| Módulo | Descripción | Estado |
|--------|-------------|--------|
| Presupuestos | Generación de presupuestos con conversión a pedido | ✅ |
| Pedidos de Venta | Gestión de pedidos con control de stock | ✅ |
| Facturas | Facturación modo local + SIFEN ready (formato KuDE) | ✅ |
| Notas de Crédito | Devoluciones parciales, totales y anulaciones | ✅ |
| Notas de Remisión | Despacho de mercadería con movimiento de stock | ✅ |
| Pagos | Registro de pagos con conciliación automática | ✅ |
| Envíos / Despachos | Seguimiento de entregas | ✅ |

### 📦 Ciclo de Compras
| Módulo | Descripción | Estado |
|--------|-------------|--------|
| Proveedores | Gestión de proveedores con historial de compras | ✅ |
| Pedidos de Compra | Órdenes de compra con seguimiento | ✅ |
| Recepción de Mercadería | Ingreso de stock con validación de cantidades | ✅ |

### 📊 Inventario y Reportes
| Módulo | Descripción | Estado |
|--------|-------------|--------|
| Productos | Catálogo con categorías, unidades e impuestos | ✅ |
| Traslados de Stock | Movimientos entre depósitos con trazabilidad | ✅ |
| Movimientos de Stock | Historial completo de entradas y salidas | ✅ |
| Reportes de Ventas | Exportación a Excel y PDF | ✅ |
| Reportes de Compras | Exportación a Excel y PDF | ✅ |
| Reportes de Stock | Inventario valorizado exportable | ✅ |
| Dashboard | KPIs, gráficos de ventas y alertas de stock bajo | ✅ |

## Arquitectura destacada

- `EmpresaScope` — Global Scope que aísla todos los queries por empresa automáticamente
- `PerteneceAEmpresa` — Trait aplicado a 19 modelos, asigna `empresa_id` al crear registros
- Clave de negocio compuesta en documentos fiscales (UNIQUE constraint, no PK física)
- Módulo de permisos con detección automática de módulos nuevos

---

Desarrollado en Paraguay 🇵🇾 | Stack: Laravel 11 + Bootstrap 5 + MariaDB
