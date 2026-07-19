@extends('layouts.app')
@section('titulo', $factura->numero_documento)
@section('contenido')

@if($factura->modo === 'local')
<div class="alert alert-warning"><i class="bi bi-exclamation-triangle me-2"></i>
Documento generado en <strong>modo demo</strong> — no tiene validez tributaria ante la SET.
</div>
@endif

<div class="row g-3">
<div class="col-md-4">
    <div class="card mb-3">
        <div class="card-header fw-semibold">Datos de la Factura</div>
        <div class="list-group list-group-flush">
            <div class="list-group-item"><small class="text-muted d-block">N° Documento</small><strong>{{ $factura->numero_documento }}</strong></div>
            <div class="list-group-item"><small class="text-muted d-block">Timbrado</small>{{ $factura->timbrado ?: 'Sin asignar' }}</div>
            <div class="list-group-item"><small class="text-muted d-block">Cliente</small><strong>{{ $factura->pedido->cliente->nombre ?? '—' }}</strong></div>
            <div class="list-group-item"><small class="text-muted d-block">Fecha</small>{{ $factura->fecha_factura->format('d/m/Y') }}</div>
            <div class="list-group-item"><small class="text-muted d-block">Condición de Venta</small>{{ ucfirst($factura->condicion_venta) }}</div>
            <div class="list-group-item"><small class="text-muted d-block">Estado</small><x-badge-estado grupo="facturas.estado" :valor="$factura->estado" /></div>
        </div>
    </div>
    <div class="card mb-3">
        <div class="card-header fw-semibold">Resumen</div>
        <div class="list-group list-group-flush">
            <div class="list-group-item d-flex justify-content-between"><span>Subtotal</span><strong>{{ number_format($factura->subtotal,0,',','.') }}</strong></div>
            @if((float) $factura->descuento_global > 0)
            <div class="list-group-item d-flex justify-content-between bg-success-subtle">
                <span class="text-success"><i class="bi bi-tag me-1"></i>Descuento {{ $factura->descuento_global }}%</span>
                <strong class="text-success">- {{ number_format($factura->monto_descuento,0,',','.') }}</strong>
            </div>
            @endif
            <div class="list-group-item d-flex justify-content-between"><span>IVA</span><strong>{{ number_format($factura->impuesto_total,0,',','.') }}</strong></div>
            <div class="list-group-item d-flex justify-content-between"><span>Total</span><strong class="fs-5">{{ number_format($factura->total,0,',','.') }}</strong></div>
            <div class="list-group-item d-flex justify-content-between"><span>Pagado</span><strong class="text-success">{{ number_format($factura->monto_pagado,0,',','.') }}</strong></div>
            <div class="list-group-item d-flex justify-content-between"><span>Saldo</span>
                <strong class="{{ $factura->saldo_pendiente > 0 ? 'text-danger' : 'text-success' }}">{{ number_format($factura->saldo_pendiente,0,',','.') }}</strong>
            </div>
        </div>
    </div>
    <div class="d-grid gap-2">
        @can('pagos.crear')
        @if(!Auth::user()?->esSuperAdmin() && $factura->saldo_pendiente > 0 && $factura->estado !== 'anulada')
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalPago"><i class="bi bi-cash-coin me-1"></i>Registrar Pago</button>
        @endif
        @endcan
        @can('facturas.crear')
        @if($factura->estado === 'pendiente' && (float) $factura->monto_pagado <= 0)
        <a href="{{ route('facturas.edit',$factura) }}" class="btn btn-outline-primary"><i class="bi bi-pencil me-1"></i>Editar Factura</a>
        @endif
        @endcan
        @if(!Auth::user()?->esSuperAdmin() && $factura->pedido)
        @can('envios.crear')
        <a href="{{ route('notas-remision.create',['pedido' => $factura->pedido_id]) }}" class="btn btn-outline-secondary"><i class="bi bi-truck me-1"></i>Generar Nota de Remisión</a>
        @endcan
        @endif
        <a href="{{ route('facturas.pdf',$factura) }}" target="_blank" class="btn btn-outline-secondary"><i class="bi bi-file-pdf me-1"></i>Ver / Descargar PDF</a>
        @if($factura->pedido && $factura->pedido->cliente)
        <a href="{{ route('facturas.index',['cliente_id' => $factura->pedido->cliente->id]) }}" class="btn btn-outline-secondary">
            <i class="bi bi-receipt-cutoff me-1"></i>Ver Facturas de {{ Str::limit($factura->pedido->cliente->nombre, 18) }}
        </a>
        @endif
        @can('facturas.crear')
        @if(!Auth::user()?->esSuperAdmin())
        <a href="{{ route('notas-credito.create',['factura' => $factura->id]) }}" class="btn btn-outline-danger"><i class="bi bi-arrow-return-left me-1"></i>Generar Nota de Crédito</a>
        @endif
        @endcan
        <a href="{{ route('facturas.index') }}" class="btn btn-outline-secondary">Volver a lista</a>
    </div>
</div>

<div class="col-md-8">
    <div class="card mb-3">
        <div class="card-header fw-semibold">Productos Facturados</div>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead><tr><th>Producto</th><th class="text-end">Cant.</th><th class="text-end">P. Unit.</th><th class="text-end">Subtotal</th></tr></thead>
                <tbody>
                @foreach($factura->pedido->detalles as $d)
                <tr>
                    <td>{{ $d->producto->nombre ?? '—' }}</td>
                    <td class="text-end">{{ number_format($d->cantidad,2) }}</td>
                    <td class="text-end">{{ number_format($d->precio_unitario,0,',','.') }}</td>
                    <td class="text-end">{{ number_format($d->subtotal,0,',','.') }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @if($factura->notasCredito->isNotEmpty())
    <div class="card mb-3">
        <div class="card-header fw-semibold">Notas de Crédito asociadas</div>
        <div class="table-responsive">
            <table class="table table-sm mb-0">
                <thead><tr><th>N° Documento</th><th>Fecha</th><th>Motivo</th><th class="text-end">Total</th><th></th></tr></thead>
                <tbody>
                @foreach($factura->notasCredito as $nc)
                <tr>
                    <td>{{ $nc->numero_completo }}</td>
                    <td>{{ $nc->fecha_emision->format('d/m/Y') }}</td>
                    <td>{{ ucfirst(str_replace('_',' ',$nc->motivo)) }}</td>
                    <td class="text-end">{{ number_format($nc->total,0,',','.') }}</td>
                    <td><a href="{{ route('notas-credito.show',$nc) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a></td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    @if($factura->pagos->isNotEmpty())
    <div class="card">
        <div class="card-header fw-semibold">Pagos Registrados</div>
        <div class="table-responsive">
            <table class="table table-sm mb-0">
                <thead><tr><th>Fecha</th><th>Método</th><th class="text-end">Monto</th></tr></thead>
                <tbody>
                @foreach($factura->pagos as $p)
                <tr><td>{{ $p->fecha_pago->format('d/m/Y') }}</td><td>{{ $p->metodoPago->nombre ?? '—' }}</td><td class="text-end">{{ number_format($p->monto,0,',','.') }}</td></tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
</div>

@can('pagos.crear')
<div class="modal fade" id="modalPago" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title">Registrar Pago — {{ $factura->numero_documento }}</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <form method="POST" action="{{ route('pagos.store') }}">@csrf
    <input type="hidden" name="factura_id" value="{{ $factura->id }}">
    <div class="modal-body">
        <div class="alert alert-light border small">Saldo pendiente: <strong>{{ number_format($factura->saldo_pendiente,0,',','.') }}</strong></div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Método de Pago *</label>
            <select name="metodo_pago_id" class="form-select" required>
                @foreach($metodosPago as $m)
                <option value="{{ $m->id }}">{{ $m->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Monto *</label>
            <input type="number" name="monto" class="form-control" step="0.01" min="0.01" max="{{ $factura->saldo_pendiente }}" value="{{ $factura->saldo_pendiente }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Fecha de Pago *</label>
            <input type="date" name="fecha_pago" class="form-control" value="{{ date('Y-m-d') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Caja</label>
            <select name="caja_id" class="form-select">
                <option value="">-- Sin caja --</option>
                @foreach($cajas as $c)
                <option value="{{ $c->id }}">{{ $c->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Cobrador</label>
            <select name="cobrador_id" class="form-select">
                <option value="">-- Sin cobrador asignado --</option>
                @foreach($cobradores as $u)
                <option value="{{ $u->id }}" {{ $u->id == auth()->id() ? 'selected' : '' }}>{{ $u->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Referencia</label>
            <input type="text" name="referencia" class="form-control" placeholder="N° de comprobante, transferencia, etc.">
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Notas</label>
            <textarea name="notas" class="form-control" rows="2"></textarea>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-success"><i class="bi bi-cash-coin me-1"></i>Registrar Pago</button>
    </div>
    </form>
    </div></div>
</div>
@endcan
@endsection
