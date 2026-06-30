@extends('layouts.app')
@section('titulo','Generar Factura')
@section('contenido')

<div class="row g-3">
    <div class="col-md-5">
        <div class="card mb-3">
            <div class="card-header fw-semibold"><i class="bi bi-file-earmark-text text-primary me-2"></i>Cargar desde Presupuesto</div>
            <div class="card-body">
                <p class="text-muted small">Si el cliente ya tiene un presupuesto <strong>aprobado</strong>, ingresá su número
                para traer automáticamente todos los datos (cliente y productos) al formulario de factura.</p>
                <form method="GET" action="{{ route('facturas.create') }}">
                    <div class="input-group">
                        <input type="text" name="presupuesto" class="form-control" placeholder="Ej: PRE-000001" required>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-download me-1"></i>Cargar datos</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card">
            <div class="card-header fw-semibold"><i class="bi bi-cart3 text-primary me-2"></i>O elegí un Pedido de Venta existente</div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light"><tr><th>Referencia</th><th>Cliente</th><th>Fecha</th><th class="text-end">Total</th><th></th></tr></thead>
                    <tbody>
                    @forelse($pedidosPendientes as $p)
                    <tr>
                        <td class="fw-semibold">{{ $p->numero_referencia }}</td>
                        <td>{{ $p->cliente->nombre ?? '—' }}</td>
                        <td>{{ $p->fecha_pedido->format('d/m/Y') }}</td>
                        <td class="text-end">{{ number_format($p->total,0,',','.') }}</td>
                        <td><a href="{{ route('facturas.create',['pedido' => $p->id]) }}" class="btn btn-sm btn-primary">Facturar</a></td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-4 text-muted">No hay pedidos pendientes de facturar</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
