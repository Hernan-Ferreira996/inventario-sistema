<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('titulo', 'Sistema') — {{ config('app.name') }}</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<style>
:root{--sidebar-w:240px}
body{background:#f1f5f9;min-height:100vh}
#sidebar{width:var(--sidebar-w);min-height:100vh;background:#1e293b;position:fixed;top:0;left:0;z-index:100;overflow-y:auto}
#sidebar .brand{color:#fff;font-size:1.1rem;font-weight:700;padding:1.2rem 1.25rem;border-bottom:1px solid #334155;display:block;text-decoration:none}
#sidebar .nav-link{color:#94a3b8;padding:.55rem 1.25rem;font-size:.9rem;border-radius:0;display:flex;align-items:center;gap:.55rem;transition:background .15s}
#sidebar .nav-link:hover,#sidebar .nav-link.active{color:#fff;background:#334155}
#sidebar .nav-section{color:#64748b;font-size:.7rem;font-weight:600;letter-spacing:.08em;padding:.6rem 1.25rem;text-transform:uppercase;cursor:pointer;display:flex;align-items:center;justify-content:space-between;user-select:none}
#sidebar .nav-section:hover{color:#94a3b8}
#sidebar .nav-section .toggle-icon{transition:transform .15s;font-size:.75rem}
#sidebar .nav-section.collapsed .toggle-icon{transform:rotate(-90deg)}
#sidebar .nav-group{overflow:hidden}
#sidebar .nav-group.collapsed{display:none}
#topbar{background:#fff;border-bottom:1px solid #e2e8f0;padding:.55rem 1.5rem;position:sticky;top:0;z-index:99}
#main-content{margin-left:var(--sidebar-w);padding:1.5rem}
.badge-estado-pendiente{background:#fbbf24;color:#000}
.badge-estado-procesando{background:#60a5fa}
.badge-estado-completado{background:#34d399;color:#000}
.badge-estado-cancelado{background:#f87171}
.badge-estado-facturado{background:#a78bfa}
.badge-estado-parcial{background:#fb923c}
.badge-estado-no_facturado,.badge-estado-no-facturado{background:#94a3b8}
.badge-estado-enviado{background:#2dd4bf;color:#000}
.badge-estado-activo{background:#22c55e}
.badge-estado-inactivo{background:#94a3b8}
</style>
</head>
<body>

<div id="sidebar">
    <a href="{{ route('dashboard') }}" class="brand"><i class="bi bi-boxes me-2"></i>Inventario Pro</a>
    <nav class="mt-2">
        <span class="nav-section">Principal</span>
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"><i class="bi bi-grid-1x2"></i>Panel</a>

        @modulo('inventario')
        @canany(['productos.ver', 'categorias.ver'])
        <span class="nav-section">Catálogos</span>
        @can('productos.ver')
        <a href="{{ route('productos.index') }}" class="nav-link {{ request()->routeIs('productos.*') ? 'active' : '' }}"><i class="bi bi-box-seam"></i>Productos</a>
        @endcan
        @can('categorias.ver')
        <a href="{{ route('categorias.index') }}" class="nav-link {{ request()->routeIs('categorias.*') ? 'active' : '' }}"><i class="bi bi-tags"></i>Categorías</a>
        @endcan
        @endcanany
        @endmodulo

        @modulo('ventas')
        @canany(['pedidos.ver', 'clientes.ver'])
        <span class="nav-section">Comercial</span>
        @can('pedidos.ver')
        <a href="{{ route('presupuestos.index') }}" class="nav-link {{ request()->routeIs('presupuestos.*') ? 'active' : '' }}"><i class="bi bi-file-earmark-text"></i>Presupuestos</a>
        <a href="{{ route('pedidos.index') }}" class="nav-link {{ request()->routeIs('pedidos.*') ? 'active' : '' }}"><i class="bi bi-cart3"></i>Pedidos de Venta</a>
        @endcan
        @can('clientes.ver')
        <a href="{{ route('clientes.index') }}" class="nav-link {{ request()->routeIs('clientes.*') ? 'active' : '' }}"><i class="bi bi-people"></i>Clientes</a>
        @endcan
        @endcanany
        @endmodulo

        @modulo('compras')
        @canany(['proveedores.ver', 'compras.ver', 'productos.editar'])
        <span class="nav-section">Compras</span>
        @can('proveedores.ver')
        <a href="{{ route('proveedores.index') }}" class="nav-link {{ request()->routeIs('proveedores.*') ? 'active' : '' }}"><i class="bi bi-building"></i>Proveedores</a>
        @endcan
        @can('compras.ver')
        <a href="{{ route('compras.index') }}" class="nav-link {{ request()->routeIs('compras.*') ? 'active' : '' }}"><i class="bi bi-bag-check"></i>Compras</a>
        @endcan
        @can('productos.editar')
        <a href="{{ route('traslados.index') }}" class="nav-link {{ request()->routeIs('traslados.*') ? 'active' : '' }}"><i class="bi bi-arrow-left-right"></i>Traslados de Stock</a>
        @endcan
        @endcanany
        @endmodulo

        @modulo('ventas')
        @canany(['facturas.ver', 'envios.ver', 'pagos.ver'])
        <span class="nav-section">Documentos</span>
        @can('facturas.ver')
        <a href="{{ route('facturas.index') }}" class="nav-link {{ request()->routeIs('facturas.*') ? 'active' : '' }}"><i class="bi bi-receipt"></i>Facturas</a>
        <a href="{{ route('notas-credito.index') }}" class="nav-link {{ request()->routeIs('notas-credito.*') ? 'active' : '' }}"><i class="bi bi-arrow-return-left"></i>Notas de Crédito</a>
        @endcan
        @can('pagos.ver')
        <a href="{{ route('pagos.index') }}" class="nav-link {{ request()->routeIs('pagos.*') ? 'active' : '' }}"><i class="bi bi-cash-coin"></i>Pagos</a>
        <a href="{{ route('cobranzas.index') }}" class="nav-link {{ request()->routeIs('cobranzas.*') ? 'active' : '' }}"><i class="bi bi-hourglass-split"></i>Cobranzas</a>
        @endcan
        @can('envios.ver')
        <a href="{{ route('notas-remision.index') }}" class="nav-link {{ request()->routeIs('notas-remision.*') ? 'active' : '' }}"><i class="bi bi-truck"></i>Notas de Remisión</a>
        <a href="{{ route('envios.index') }}" class="nav-link {{ request()->routeIs('envios.*') ? 'active' : '' }}"><i class="bi bi-box-seam"></i>Envíos</a>
        @endcan
        @endcanany
        @endmodulo

        @modulo('reportes')
        @can('reportes.ver')
        <span class="nav-section">Reportes</span>
        <a href="{{ route('reportes.stock') }}" class="nav-link {{ request()->routeIs('reportes.stock') ? 'active' : '' }}"><i class="bi bi-bar-chart-line"></i>Stock</a>
        <a href="{{ route('reportes.ventas') }}" class="nav-link {{ request()->routeIs('reportes.ventas') ? 'active' : '' }}"><i class="bi bi-graph-up-arrow"></i>Ventas</a>
        @endcan
        @endmodulo

        @modulo('contabilidad')
        @can('contabilidad.ver')
        <span class="nav-section">Contabilidad</span>
        <a href="{{ route('contabilidad.asientos.index') }}" class="nav-link {{ request()->routeIs('contabilidad.asientos.*') ? 'active' : '' }}"><i class="bi bi-journal-text"></i>Libro Diario</a>
        <a href="{{ route('contabilidad.cuentas.index') }}" class="nav-link {{ request()->routeIs('contabilidad.cuentas.*') ? 'active' : '' }}"><i class="bi bi-diagram-3"></i>Plan de Cuentas</a>
        <a href="{{ route('contabilidad.reportes.balance-general') }}" class="nav-link {{ request()->routeIs('contabilidad.reportes.*') ? 'active' : '' }}"><i class="bi bi-bar-chart-steps"></i>Reportes Contables</a>
        @endcan
        @endmodulo

        @role('admin')
        <span class="nav-section">Administración</span>
        <a href="{{ route('usuarios.index') }}" class="nav-link {{ request()->routeIs('usuarios.*') ? 'active' : '' }}"><i class="bi bi-people"></i>Usuarios</a>
        <a href="{{ route('grupos.index') }}" class="nav-link {{ request()->routeIs('grupos.*') ? 'active' : '' }}"><i class="bi bi-shield-gear"></i>Grupos de Acceso</a>
        @modulo('auditoria')
        <a href="{{ route('auditoria.index') }}" class="nav-link {{ request()->routeIs('auditoria.*') ? 'active' : '' }}"><i class="bi bi-clock-history"></i>Auditoría</a>
        @endmodulo
        @endrole
        @if(Auth::user()?->esSuperAdmin())
        <span class="nav-section">Sistema</span>
        <a href="{{ route('empresas.index') }}" class="nav-link {{ request()->routeIs('empresas.*') ? 'active' : '' }}"><i class="bi bi-building-gear"></i>Empresas</a>
        @endif
        @role('admin')
        <a href="{{ route('configuracion.index') }}" class="nav-link {{ request()->routeIs('configuracion.*') ? 'active' : '' }}"><i class="bi bi-gear"></i>Configuración</a>
        @endrole
    </nav>
</div>

<div id="main-content">
    <div id="topbar" class="d-flex justify-content-between align-items-center mb-3 rounded">
        <div>
            <span class="fw-semibold text-secondary">@yield('titulo', 'Panel')</span>
            @if(Auth::user()?->empresa_id && Auth::user()->empresa)
            <span class="badge bg-light text-secondary border ms-2 small">
                <i class="bi bi-building me-1"></i>{{ Auth::user()->empresa->nombre_fantasia ?: Auth::user()->empresa->nombre }}
            </span>
            @elseif(Auth::user()?->esSuperAdmin())
            <span class="badge bg-warning text-dark border ms-2 small"><i class="bi bi-shield-fill me-1"></i>Super Admin</span>
            @endif
        </div>
        <div class="d-flex align-items-center gap-3">
            @if(isset($alertasStockBajo) && $alertasStockBajo->isNotEmpty())
            <div class="dropdown">
                <a href="#" class="position-relative text-dark" data-bs-toggle="dropdown" title="Alertas de stock bajo">
                    <i class="bi bi-bell fs-5"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:.6rem">
                        {{ $alertasStockBajo->count() }}
                    </span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" style="width:300px">
                    <li><h6 class="dropdown-header"><i class="bi bi-exclamation-triangle text-warning me-1"></i>Stock bajo</h6></li>
                    @foreach($alertasStockBajo as $p)
                    <li>
                        <a href="{{ route('productos.show',$p) }}" class="dropdown-item d-flex justify-content-between align-items-center">
                            <span class="text-truncate" style="max-width:180px">{{ $p->nombre }}</span>
                            <span class="badge bg-danger">{{ number_format($p->movimientos_sum_cantidad ?? 0,0) }}</span>
                        </a>
                    </li>
                    @endforeach
                    <li><hr class="dropdown-divider"></li>
                    <li><a href="{{ route('productos.index',['stock_bajo'=>1]) }}" class="dropdown-item text-center text-primary small">Ver todos</a></li>
                </ul>
            </div>
            @endif
            <div class="dropdown">
                <a href="#" class="dropdown-toggle text-decoration-none text-dark d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle fs-5"></i>
                    <span>{{ Auth::user()?->name ?? 'Usuario' }}</span>
                    @if(Auth::user()?->roles->isNotEmpty())
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle">{{ ucfirst(Auth::user()->roles->first()->name) }}</span>
                    @endif
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><span class="dropdown-item-text small text-muted">{{ Auth::user()?->email }}</span></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">@csrf
                            <button class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    @if($errors->any())
    <div class="alert alert-warning alert-dismissible fade show mb-3">
        <i class="bi bi-exclamation-circle me-2"></i><strong>Revisa los campos:</strong>
        <ul class="mb-0 mt-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @yield('contenido')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
@stack('scripts')
<script>
(function () {
    var nav = document.querySelector('#sidebar nav');
    if (!nav) return;
    Array.prototype.slice.call(nav.querySelectorAll('.nav-section')).forEach(function (sectionEl) {
        var label = sectionEl.textContent.trim();

        var group = document.createElement('div');
        group.className = 'nav-group';
        var el = sectionEl.nextElementSibling;
        while (el && !el.classList.contains('nav-section')) {
            var next = el.nextElementSibling;
            group.appendChild(el);
            el = next;
        }
        sectionEl.insertAdjacentElement('afterend', group);
        sectionEl.insertAdjacentHTML('beforeend', '<i class="bi bi-chevron-down toggle-icon"></i>');

        var key = 'sidebarSeccion_' + label.replace(/\s+/g, '_');
        var activeInside = group.querySelector('.nav-link.active') !== null;
        var stored = localStorage.getItem(key);
        var defaultCollapsed = label !== 'Principal';
        var collapsed = stored !== null ? stored === '1' : defaultCollapsed;

        if (collapsed && !activeInside) {
            group.classList.add('collapsed');
            sectionEl.classList.add('collapsed');
        }

        sectionEl.addEventListener('click', function () {
            var nowCollapsed = group.classList.toggle('collapsed');
            sectionEl.classList.toggle('collapsed', nowCollapsed);
            localStorage.setItem(key, nowCollapsed ? '1' : '0');
        });
    });
})();
</script>
</body>
</html>