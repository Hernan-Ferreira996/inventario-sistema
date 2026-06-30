@extends('layouts.app')
@section('titulo', 'Panel Principal')

@section('contenido')

{{-- Tarjetas de estadísticas --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="value">{{ number_format($estadisticas['total_productos']) }}</div>
                    <div class="label">Productos activos</div>
                </div>
                <div class="icon bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-box-seam"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="value">{{ number_format($estadisticas['total_clientes']) }}</div>
                    <div class="label">Clientes</div>
                </div>
                <div class="icon bg-success bg-opacity-10 text-success">
                    <i class="bi bi-people"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="value">{{ number_format($estadisticas['pedidos_hoy']) }}</div>
                    <div class="label">Pedidos hoy</div>
                </div>
                <div class="icon bg-warning bg-opacity-10 text-warning">
                    <i class="bi bi-cart3"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="value">{{ number_format($estadisticas['ventas_mes'], 2) }}</div>
                    <div class="label">Ventas del mes</div>
                </div>
                <div class="icon bg-info bg-opacity-10 text-info">
                    <i class="bi bi-currency-dollar"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    {{-- Gráfico de ventas --}}
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center py-3">
                <span><i class="bi bi-graph-up me-2 text-primary"></i>Ventas últimos 30 días</span>
            </div>
            <div class="card-body">
                <canvas id="graficaVentas" height="80"></canvas>
            </div>
        </div>
    </div>

    {{-- Alertas --}}
    <div class="col-xl-4">
        <div class="card h-100">
            <div class="card-header py-3">
                <i class="bi bi-bell me-2 text-warning"></i>Alertas
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <div>
                            <div class="fw-semibold text-sm">Pedidos pendientes de facturar</div>
                            <small class="text-muted">Requieren acción</small>
                        </div>
                        <span class="badge bg-warning text-dark rounded-pill fs-6">{{ $estadisticas['pedidos_pendientes'] }}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <div>
                            <div class="fw-semibold">Compras pendientes</div>
                            <small class="text-muted">Por recibir</small>
                        </div>
                        <span class="badge bg-info rounded-pill fs-6">{{ $estadisticas['compras_pendientes'] }}</span>
                    </div>
                    @forelse($stockBajo as $producto)
                    <div class="list-group-item py-2">
                        <div class="d-flex justify-content-between">
                            <small class="fw-semibold">{{ $producto->nombre }}</small>
                            <span class="badge bg-danger rounded-pill">{{ $producto->movimientos_sum_cantidad ?? 0 }}</span>
                        </div>
                        <small class="text-danger">Stock bajo</small>
                    </div>
                    @empty
                    <div class="list-group-item text-center py-3">
                        <small class="text-muted">Sin alertas de stock</small>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- Últimos pedidos --}}
    <div class="col-xl-7">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center py-3">
                <span><i class="bi bi-clock-history me-2 text-primary"></i>Últimos Pedidos</span>
                <a href="{{ route('pedidos.index') }}" class="btn btn-sm btn-outline-primary">Ver todos</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Referencia</th>
                            <th>Cliente</th>
                            <th>Fecha</th>
                            <th>Total</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ultimosPedidos as $pedido)
                        <tr>
                            <td><a href="{{ route('pedidos.show', $pedido) }}" class="fw-semibold text-decoration-none">{{ $pedido->numero_referencia }}</a></td>
                            <td>{{ $pedido->cliente->nombre ?? '—' }}</td>
                            <td>{{ $pedido->fecha_pedido->format('d/m/Y') }}</td>
                            <td class="fw-semibold">{{ number_format($pedido->total, 2) }}</td>
                            <td><span class="badge badge-estado-{{ $pedido->estado }}">{{ ucfirst($pedido->estado) }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">Sin pedidos registrados</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Pedidos por facturar --}}
    <div class="col-xl-5">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center py-3">
                <span><i class="bi bi-receipt me-2 text-warning"></i>Por Facturar</span>
                <a href="{{ route('pedidos.index') }}?estado_factura=pendiente" class="btn btn-sm btn-outline-warning">Ver todos</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Total</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pedidosPorFacturar as $pedido)
                        <tr>
                            <td>{{ $pedido->cliente->nombre ?? '—' }}</td>
                            <td class="fw-semibold">{{ number_format($pedido->total, 2) }}</td>
                            <td>
                                <a href="{{ route('pedidos.show', $pedido) }}" class="btn btn-xs btn-outline-primary btn-sm">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted py-4">Todo al día</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
const ventasData = @json($ventasUltimos30);
const labels = ventasData.map(v => {
    const [y,m,d] = v.fecha.split('-');
    return `${d}/${m}`;
});
const valores = ventasData.map(v => parseFloat(v.total));

new Chart(document.getElementById('graficaVentas'), {
    type: 'line',
    data: {
        labels,
        datasets: [{
            label: 'Ventas',
            data: valores,
            borderColor: '#2563eb',
            backgroundColor: 'rgba(37,99,235,0.08)',
            tension: 0.4,
            fill: true,
            pointRadius: 3,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: '#f1f5f9' } },
            x: { grid: { display: false } }
        }
    }
});
</script>
@endpush
