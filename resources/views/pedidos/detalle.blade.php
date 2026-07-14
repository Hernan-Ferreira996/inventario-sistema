@extends('layouts.app')
@section('titulo', $pedido->numero_referencia)
@section('contenido')

<div class="row g-3">
    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header fw-semibold">Información del Pedido</div>
            <div class="list-group list-group-flush">
                <div class="list-group-item"><small class="text-muted d-block">Referencia</small><strong>{{ $pedido->numero_referencia }}</strong></div>
                <div class="list-group-item"><small class="text-muted d-block">Cliente</small><strong>{{ $pedido->cliente?->nombre ?? '—' }}</strong></div>
                <div class="list-group-item"><small class="text-muted d-block">Fecha Pedido</small>{{ $pedido->fecha_pedido->format('d/m/Y') }}</div>
                <div class="list-group-item"><small class="text-muted d-block">Fecha Entrega</small>{{ $pedido->fecha_entrega?->format('d/m/Y') ?? '—' }}</div>
                <div class="list-group-item"><small class="text-muted d-block">Estado</small><x-badge-estado grupo="pedidos_venta.estado" :valor="$pedido->estado" /></div>
                <div class="list-group-item"><small class="text-muted d-block">Factura</small><x-badge-estado grupo="pedidos_venta.estado_factura" :valor="$pedido->estado_factura" /></div>
            </div>
        </div>
        <div class="card">
            <div class="card-header fw-semibold">Resumen Financiero</div>
            <div class="list-group list-group-flush">
                <div class="list-group-item d-flex justify-content-between"><span>Total</span><strong>{{ number_format($pedido->total,2) }}</strong></div>
                <div class="list-group-item d-flex justify-content-between"><span>Pagado</span><strong class="text-success">{{ number_format($pedido->monto_pagado,2) }}</strong></div>
                <div class="list-group-item d-flex justify-content-between"><span>Saldo</span>
                    <strong class="{{ $pedido->saldo_pendiente > 0 ? 'text-danger' : 'text-success' }}">{{ number_format($pedido->saldo_pendiente,2) }}</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Productos del Pedido</span>
                <span class="badge bg-secondary">{{ $pedido->detalles->count() }} items</span>
            </div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead><tr><th>Producto</th><th class="text-end">Cant.</th><th class="text-end">P. Unit.</th><th class="text-end">Desc%</th><th class="text-end">Subtotal</th></tr></thead>
                    <tbody>
                        @foreach($pedido->detalles as $d)
                        <tr>
                            <td><div class="fw-semibold">{{ $d->producto?->nombre ?? '—' }}</div><small class="text-muted">{{ $d->producto?->codigo }}</small></td>
                            <td class="text-end">{{ number_format($d->cantidad,2) }}</td>
                            <td class="text-end">{{ number_format($d->precio_unitario,2) }}</td>
                            <td class="text-end">{{ $d->descuento }}%</td>
                            <td class="text-end fw-semibold">{{ number_format($d->subtotal,2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot><tr class="fw-bold bg-light"><td colspan="4" class="text-end">TOTAL:</td><td class="text-end">{{ number_format($pedido->total,2) }}</td></tr></tfoot>
                </table>
            </div>
        </div>

        @if($pedido->comentarios)
        <div class="card mb-3"><div class="card-body">
            <small class="text-muted d-block mb-1">Comentarios:</small>
            {{ $pedido->comentarios }}
        </div></div>
        @endif

        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Documentos</span>
                <div class="d-flex gap-2">
                    @if(!Auth::user()?->esSuperAdmin())
                    @can('facturas.crear')
                    @if($pedido->facturas->isEmpty())
                    <a href="{{ route('facturas.create',['pedido' => $pedido->id]) }}" class="btn btn-sm btn-primary"><i class="bi bi-receipt me-1"></i>Generar Factura</a>
                    @endif
                    @endcan
                    @can('envios.crear')
                    <a href="{{ route('notas-remision.create',['pedido' => $pedido->id]) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-truck me-1"></i>Nota de Remisión</a>
                    <a href="{{ route('envios.create',['pedido' => $pedido->id]) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-box-seam me-1"></i>Registrar Envío</a>
                    @endcan
                    @endif
                </div>
            </div>
            @if($pedido->facturas->isNotEmpty())
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead><tr><th>Factura</th><th>Fecha</th><th class="text-end">Total</th><th>Estado</th><th></th></tr></thead>
                    <tbody>
                    @foreach($pedido->facturas as $f)
                    <tr>
                        <td>{{ $f->numero_documento }}</td>
                        <td>{{ $f->fecha_factura->format('d/m/Y') }}</td>
                        <td class="text-end">{{ number_format($f->total,0,',','.') }}</td>
                        <td><x-badge-estado grupo="facturas.estado" :valor="$f->estado" /></td>
                        <td><a href="{{ route('facturas.show',$f) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a></td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="card-body text-muted text-center py-3">Sin factura generada todavía</div>
            @endif
        </div>

        <a href="{{ route('pedidos.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Volver a lista
        </a>
    </div>
</div>
@endsection
