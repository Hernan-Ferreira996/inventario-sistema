@extends('layouts.app')
@section('titulo','Nueva Nota de Crédito')
@section('contenido')

@if($config['fact_modo'] === 'local')
<div class="alert alert-warning"><i class="bi bi-exclamation-triangle me-2"></i>Documento en modo demo, sin validez tributaria.</div>
@endif

<div class="card">
    <div class="card-header fw-semibold">Nota de Crédito — Factura {{ $factura->numero_documento }}</div>
    <div class="card-body">
        <form method="POST" action="{{ route('notas-credito.store') }}" id="formNC">
            @csrf
            <input type="hidden" name="factura_id" value="{{ $factura->id }}">

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Próximo N° de Documento</label>
                    <input type="text" class="form-control bg-light" value="{{ $config['fact_establecimiento'] }}-{{ $config['fact_punto_expedicion'] }}-{{ $proximoNumero }}" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Motivo *</label>
                    <select name="motivo" class="form-select" required>
                        <option value="devolucion_parcial">Devolución parcial</option>
                        <option value="devolucion_total">Devolución total</option>
                        <option value="descuento">Descuento posterior</option>
                        <option value="anulacion">Anulación de factura</option>
                        <option value="otro">Otro</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Cliente</label>
                    <input type="text" class="form-control bg-light" value="{{ $factura->pedido->cliente->nombre ?? '' }}" readonly>
                </div>
                <div class="col-md-6" id="campoUbicacion">
                    <label class="form-label fw-semibold">Almacén que recibe la devolución *</label>
                    <select name="ubicacion_id" class="form-select">
                        <option value="">-- Seleccionar --</option>
                        @foreach($ubicaciones as $u)
                        <option value="{{ $u->id }}">{{ $u->nombre }}</option>
                        @endforeach
                    </select>
                    <small class="text-muted">El stock devuelto se sumará a este almacén</small>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Descripción del motivo</label>
                    <textarea name="descripcion_motivo" class="form-control" rows="2"></textarea>
                </div>
            </div>

            <h6 class="fw-bold mb-2 border-bottom pb-2">Productos a Acreditar</h6>
            <div id="lineas-nc">
            @foreach($factura->pedido->detalles as $i => $d)
                <div class="row g-2 mb-2 linea-item align-items-center">
                    <div class="col-md-1"><input type="checkbox" class="form-check-input chk-incluir" checked onchange="toggleLinea(this)"></div>
                    <div class="col-md-5">
                        <input type="hidden" name="productos[{{ $i }}][producto_id]" value="{{ $d->producto_id }}">
                        <span>{{ $d->producto->nombre ?? '—' }}</span>
                    </div>
                    <div class="col-md-3"><input type="number" name="productos[{{ $i }}][cantidad]" class="form-control" step="0.01" min="0.01" max="{{ $d->cantidad }}" value="{{ $d->cantidad }}"></div>
                    <div class="col-md-3"><input type="number" name="productos[{{ $i }}][precio_unitario]" class="form-control" step="0.01" value="{{ $d->precio_unitario }}"></div>
                </div>
            @endforeach
            </div>

            <div class="d-flex gap-2 border-top pt-3 mt-3">
                <button type="submit" class="btn btn-danger px-4"><i class="bi bi-arrow-return-left me-1"></i>Generar Nota de Crédito</button>
                <a href="{{ route('facturas.show',$factura) }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@push('scripts')
<script>
function toggleLinea(chk) {
    const linea = chk.closest('.linea-item');
    linea.querySelectorAll('input[type=number]').forEach(i => i.disabled = !chk.checked);
    if (!chk.checked) linea.querySelector('input[type=hidden]').disabled = true;
    else linea.querySelector('input[type=hidden]').disabled = false;
}
function actualizarCampoUbicacion() {
    const motivo = document.querySelector('select[name=motivo]').value;
    const restablece = ['devolucion_total','devolucion_parcial','anulacion'].includes(motivo);
    const campo = document.getElementById('campoUbicacion');
    const select = campo.querySelector('select');
    campo.style.display = restablece ? '' : 'none';
    select.required = restablece;
}
document.querySelector('select[name=motivo]').addEventListener('change', actualizarCampoUbicacion);
actualizarCampoUbicacion();
</script>
@endpush
@endsection
