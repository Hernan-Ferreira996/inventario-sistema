@extends('layouts.app')
@section('titulo', $pedidoCompra->numero_referencia)
@section('contenido')
<div class="row g-3">
<div class="col-md-4">
    <div class="card mb-3">
        <div class="card-header fw-semibold">Datos del Pedido</div>
        <div class="list-group list-group-flush">
            <div class="list-group-item"><small class="text-muted d-block">Referencia</small><strong>{{ $pedidoCompra->numero_referencia }}</strong></div>
            <div class="list-group-item"><small class="text-muted d-block">Proveedor</small><strong>{{ $pedidoCompra->proveedor->nombre }}</strong></div>
            <div class="list-group-item"><small class="text-muted d-block">Tipo</small>
                <x-badge-estado grupo="pedidos_compra.tipo" :valor="$pedidoCompra->tipo" />
            </div>
            <div class="list-group-item"><small class="text-muted d-block">Fecha Pedido</small>{{ $pedidoCompra->fecha_pedido->format('d/m/Y') }}</div>
            <div class="list-group-item"><small class="text-muted d-block">Fecha Esperada</small>{{ $pedidoCompra->fecha_esperada?->format('d/m/Y') ?? 'Sin fecha' }}</div>
            <div class="list-group-item"><small class="text-muted d-block">Estado</small>
                <x-badge-estado grupo="pedidos_compra.estado" :valor="$pedidoCompra->estado" />
            </div>
            <div class="list-group-item"><small class="text-muted d-block">Total</small>
                <strong class="fs-5">{{ number_format($pedidoCompra->total,2) }}</strong>
            </div>
        </div>
    </div>
    <div class="d-grid gap-2">
        @if(!in_array($pedidoCompra->estado, ['completado','cancelado']))
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalRecepcion">
            <i class="bi bi-box-arrow-in-down me-1"></i>Recibir Mercancia
        </button>
        <a href="{{ route('compras.edit',$pedidoCompra) }}" class="btn btn-outline-warning"><i class="bi bi-pencil me-1"></i>Editar</a>
        @endif
        <a href="{{ route('compras.pdf',$pedidoCompra) }}" target="_blank" class="btn btn-outline-secondary"><i class="bi bi-file-pdf me-1"></i>Ver / Descargar PDF</a>
        <a href="{{ route('compras.index') }}" class="btn btn-outline-secondary">Volver a lista</a>
    </div>
</div>
<div class="col-md-8">
    <div class="card mb-3">
        <div class="card-header fw-semibold">Productos Pedidos</div>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead><tr><th>Producto</th><th class="text-end">Pedida</th><th class="text-end">P. Unit.</th><th class="text-end">Recibida</th><th class="text-end">Subtotal</th></tr></thead>
                <tbody>
                @foreach($pedidoCompra->detalles as $d)
                <tr>
                    <td><div class="fw-semibold">{{ $d->producto?->nombre ?? 'Eliminado' }}</div><small class="text-muted">{{ $d->producto?->codigo }}</small></td>
                    <td class="text-end">{{ number_format($d->cantidad,2) }}</td>
                    <td class="text-end">{{ number_format($d->precio_unitario,2) }}</td>
                    <td class="text-end {{ $d->cantidad_recibida >= $d->cantidad ? 'text-success fw-bold' : 'text-warning' }}">{{ number_format($d->cantidad_recibida,2) }}</td>
                    <td class="text-end">{{ number_format($d->subtotal,2) }}</td>
                </tr>
                @endforeach
                </tbody>
                <tfoot><tr class="fw-bold bg-light"><td colspan="4" class="text-end">TOTAL:</td><td class="text-end">{{ number_format($pedidoCompra->total,2) }}</td></tr></tfoot>
            </table>
        </div>
    </div>
    @if($pedidoCompra->recepciones->isNotEmpty())
    <div class="card">
        <div class="card-header fw-semibold">Recepciones de Mercancia</div>
        @foreach($pedidoCompra->recepciones as $rec)
        <div class="card-body border-bottom py-2">
            <div class="d-flex justify-content-between mb-2">
                <strong>{{ $rec->fecha_recepcion->format('d/m/Y') }}</strong>
                <small class="text-muted">Ref: {{ $rec->numero_referencia ?? 'Sin ref.' }}</small>
            </div>
            <table class="table table-sm mb-0">
                <thead><tr><th>Producto</th><th>Ubicacion</th><th class="text-end">Cant.</th></tr></thead>
                <tbody>
                @foreach($rec->detalles as $rd)
                <tr>
                    <td>{{ $rd->producto?->nombre ?? 'Eliminado' }}</td>
                    <td>{{ $rd->ubicacion?->nombre ?? 'Sin ubicacion' }}</td>
                    <td class="text-end text-success fw-bold">+{{ number_format($rd->cantidad,2) }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
            @if($rec->notas)<p class="text-muted small mb-0 mt-1">{{ $rec->notas }}</p>@endif
        </div>
        @endforeach
    </div>
    @endif
</div>
</div>

@if(!in_array($pedidoCompra->estado, ['completado','cancelado']))
<div class="modal fade" id="modalRecepcion" tabindex="-1">
    <div class="modal-dialog modal-lg"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title">Recibir Mercancia</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <form method="POST" action="{{ route('compras.recibir',$pedidoCompra) }}">@csrf
    <div class="modal-body">
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">Almacen destino *</label>
                <select name="ubicacion_id" class="form-select" required>
                    @foreach($ubicaciones as $u)
                    <option value="{{ $u->id }}">{{ $u->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Referencia / Factura Proveedor</label>
                <input type="text" name="referencia" class="form-control" placeholder="Ej: FAC-0001">
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold">Notas</label>
                <textarea name="notas" class="form-control" rows="2"></textarea>
            </div>
        </div>
        <h6 class="fw-semibold mb-2">Cantidades a recibir</h6>
        <table class="table table-sm">
            <thead><tr><th>Producto</th><th class="text-end">Pedida</th><th class="text-end">Ya recibida</th><th class="text-end" style="width:140px">Recibir ahora</th></tr></thead>
            <tbody>
            @foreach($pedidoCompra->detalles as $d)
            @php $pendiente = $d->cantidad - $d->cantidad_recibida; @endphp
            @if($pendiente > 0)
            <tr>
                <td>{{ $d->producto?->nombre ?? 'Eliminado' }}<input type="hidden" name="items[{{ $loop->index }}][detalle_id]" value="{{ $d->id }}"></td>
                <td class="text-end">{{ number_format($d->cantidad,2) }}</td>
                <td class="text-end text-muted">{{ number_format($d->cantidad_recibida,2) }}</td>
                <td class="text-end"><input type="number" name="items[{{ $loop->index }}][cantidad]" class="form-control form-control-sm text-end" step="0.01" min="0" max="{{ $pendiente }}" value="{{ $pendiente }}"></td>
            </tr>
            @endif
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-success"><i class="bi bi-box-arrow-in-down me-1"></i>Confirmar Recepcion</button>
    </div>
    </form>
    </div></div>
</div>
@endif
@endsection
