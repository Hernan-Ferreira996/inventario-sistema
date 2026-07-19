@extends('layouts.app')
@section('titulo','Generar Factura')
@section('contenido')

@if($config['fact_modo'] === 'local')
<div class="alert alert-warning"><i class="bi bi-exclamation-triangle me-2"></i>
Esta factura se generará en <strong>modo demo/local</strong> — no es un documento tributario válido ante la SET.
Para emitir la factura oficial, segui usando e-Kuatia con estos mismos datos.
</div>
@endif
@if($pedido->cliente?->exento_iva)
<div class="alert alert-info"><i class="bi bi-info-circle me-2"></i>
{{ $pedido->cliente->nombre }} está marcado como <strong>exento de IVA</strong>: esta factura se generará sin impuesto.
</div>
@endif

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-semibold">Generar Factura — Pedido {{ $pedido->numero_referencia }}</span>
        @can('facturas.ver')
        <a href="{{ route('facturas.index',['cliente_id' => $pedido->cliente->id]) }}" class="btn btn-sm btn-outline-secondary" target="_blank">
            <i class="bi bi-receipt-cutoff me-1"></i>Ver Facturas de {{ $pedido->cliente->nombre }}
        </a>
        @endcan
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('facturas.store') }}">
            @csrf
            <input type="hidden" name="pedido_id" value="{{ $pedido->id }}">

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Próximo N° de Documento</label>
                    <input type="text" class="form-control bg-light" value="{{ $config['fact_establecimiento'] }}-{{ $config['fact_punto_expedicion'] }}-{{ $proximoNumero }}" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Cliente</label>
                    <input type="text" class="form-control bg-light" value="{{ $pedido->cliente->nombre }}" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Condición de Venta *</label>
                    <select name="condicion_venta" class="form-select" required>
                        <option value="contado">Contado</option>
                        <option value="credito">Crédito</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Tipo Documento Cliente</label>
                    <select name="tipo_documento_cliente" class="form-select">
                        <option value="CI">Cédula de Identidad</option>
                        <option value="RUC">RUC</option>
                        <option value="PAS">Pasaporte</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">N° Documento Cliente</label>
                    <input type="text" name="numero_documento_cliente" class="form-control" placeholder="1886531-3">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Descuento Global</label>
                    <div class="input-group">
                        <input type="number" name="descuento_global" id="descGlobal" class="form-control"
                            step="0.01" min="0" max="100" value="0"
                            oninput="recalcularTotal()">
                        <span class="input-group-text">%</span>
                    </div>
                    <small class="text-muted">Se aplica sobre el subtotal total del documento</small>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Monto Descuento</label>
                    <div class="input-group">
                        <span class="input-group-text">Gs.</span>
                        <input type="text" id="montoDescGlobal" class="form-control bg-light" readonly value="0">
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Total con Descuento</label>
                    <div class="input-group">
                        <span class="input-group-text">Gs.</span>
                        <input type="text" id="totalConDesc" class="form-control bg-light fw-bold" readonly value="{{ number_format($pedido->total,0,',','.') }}">
                    </div>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Notas</label>
                    <textarea name="notas" class="form-control" rows="2"></textarea>
                </div>
            </div>

            <h6 class="fw-bold mb-2 border-bottom pb-2">Productos del Pedido</h6>
            <table class="table table-sm">
                <thead><tr><th>Producto</th><th class="text-end">Cant.</th><th class="text-end">P. Unitario</th><th class="text-end">Subtotal</th></tr></thead>
                <tbody>
                @foreach($pedido->detalles as $d)
                <tr>
                    <td>{{ $d->producto->nombre ?? '—' }}</td>
                    <td class="text-end">{{ number_format($d->cantidad,2) }}</td>
                    <td class="text-end">{{ number_format($d->precio_unitario,0,',','.') }}</td>
                    <td class="text-end">{{ number_format($d->subtotal,0,',','.') }}</td>
                </tr>
                @endforeach
                </tbody>
                <tfoot><tr class="fw-bold bg-light"><td colspan="3" class="text-end">TOTAL:</td><td class="text-end">{{ number_format($pedido->total,0,',','.') }}</td></tr></tfoot>
            </table>

            <div class="d-flex gap-2 border-top pt-3 mt-3">
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-receipt me-1"></i>Generar Factura</button>
                <a href="{{ route('pedidos.show',$pedido) }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
const subtotalBase = {{ (int) $pedido->total }};
function recalcularTotal() {
    const pct = parseFloat(document.getElementById('descGlobal').value) || 0;
    const monto = Math.round(subtotalBase * pct / 100);
    const total  = subtotalBase - monto;
    document.getElementById('montoDescGlobal').value = monto.toLocaleString('es-PY');
    document.getElementById('totalConDesc').value = total.toLocaleString('es-PY');
    // Resaltar si hay descuento activo
    document.getElementById('totalConDesc').classList.toggle('text-success', pct > 0);
}
</script>
@endpush
@endsection
