@extends('layouts.app')
@section('titulo','Editar Factura')
@section('contenido')

<div class="alert alert-info"><i class="bi bi-info-circle me-2"></i>
Solo se puede editar mientras la factura esté <strong>pendiente y sin pagos registrados</strong>.
Al guardar, se actualizan tanto la factura como el pedido de venta asociado.
</div>

<div class="card">
    <div class="card-header fw-semibold">Editar Factura {{ $factura->numero_documento }} — {{ $factura->pedido->cliente->nombre ?? '' }}</div>
    <div class="card-body">
        <form method="POST" action="{{ route('facturas.update',$factura) }}" id="formFacturaEditar">
            @csrf @method('PATCH')

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Condición de Venta *</label>
                    <select name="condicion_venta" class="form-select" required>
                        <option value="contado" {{ $factura->condicion_venta === 'contado' ? 'selected' : '' }}>Contado</option>
                        <option value="credito" {{ $factura->condicion_venta === 'credito' ? 'selected' : '' }}>Crédito</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tipo Documento Cliente</label>
                    <select name="tipo_documento_cliente" class="form-select">
                        <option value="CI" {{ $factura->tipo_documento_cliente === 'CI' ? 'selected' : '' }}>Cédula de Identidad</option>
                        <option value="RUC" {{ $factura->tipo_documento_cliente === 'RUC' ? 'selected' : '' }}>RUC</option>
                        <option value="PAS" {{ $factura->tipo_documento_cliente === 'PAS' ? 'selected' : '' }}>Pasaporte</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">N° Documento Cliente</label>
                    <input type="text" name="numero_documento_cliente" class="form-control" value="{{ $factura->numero_documento_cliente }}">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Notas</label>
                    <textarea name="notas" class="form-control" rows="2">{{ $factura->notas }}</textarea>
                </div>
            </div>

            <h6 class="fw-bold mb-2 border-bottom pb-2">Productos</h6>
            <div id="lineas-factura">
            @foreach($factura->pedido->detalles as $i => $d)
                <div class="row g-2 mb-2 linea-item align-items-center">
                    <div class="col-md-5">
                        <select name="productos[{{ $i }}][producto_id]" class="form-select select-producto" required>
                            <option value="">-- Seleccionar producto --</option>
                            @foreach($productos as $p)
                            <option value="{{ $p->id }}" data-precio="{{ $p->precio_venta_minorista }}" {{ $d->producto_id == $p->id ? 'selected' : '' }}>{{ $p->codigo }} — {{ $p->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2"><input type="number" name="productos[{{ $i }}][cantidad]" class="form-control" step="0.01" min="0.01" value="{{ $d->cantidad }}" required></div>
                    <div class="col-md-2"><div class="input-group"><span class="input-group-text">Gs.</span><input type="number" name="productos[{{ $i }}][precio_unitario]" class="form-control precio-input" step="0.01" min="0" value="{{ $d->precio_unitario }}" required></div></div>
                    <div class="col-md-2"><div class="input-group"><input type="number" name="productos[{{ $i }}][descuento]" class="form-control" step="0.01" min="0" max="100" value="{{ $d->descuento }}"><span class="input-group-text">%</span></div></div>
                    <div class="col-md-1 text-end"><button type="button" class="btn btn-outline-danger btn-sm" onclick="quitarLinea(this)"><i class="bi bi-x"></i></button></div>
                </div>
            @endforeach
            </div>
            <button type="button" class="btn btn-outline-success btn-sm mb-4" onclick="agregarLinea()"><i class="bi bi-plus-lg me-1"></i>Agregar producto</button>

            <div class="d-flex gap-2 border-top pt-3">
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i>Guardar Cambios</button>
                <a href="{{ route('facturas.show',$factura) }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
let idx = {{ $factura->pedido->detalles->count() }};
function agregarLinea() {
    const cont = document.getElementById('lineas-factura');
    const primera = cont.querySelector('.linea-item');
    const nueva = primera.cloneNode(true);
    nueva.querySelectorAll('input').forEach(i => { i.name = i.name.replace(/\[\d+\]/, `[${idx}]`); if(i.name.includes('cantidad')) i.value=1; else i.value=0; });
    nueva.querySelectorAll('select').forEach(s => { s.name = s.name.replace(/\[\d+\]/, `[${idx}]`); s.value=''; });
    idx++;
    cont.appendChild(nueva);
    nueva.querySelector('.select-producto').addEventListener('change', autocompletarPrecio);
}
function quitarLinea(btn) {
    if (document.querySelectorAll('.linea-item').length > 1) btn.closest('.linea-item').remove();
}
function autocompletarPrecio(e) {
    const opt = e.target.options[e.target.selectedIndex];
    e.target.closest('.linea-item').querySelector('.precio-input').value = opt.getAttribute('data-precio') || 0;
}
document.querySelectorAll('.select-producto').forEach(s => s.addEventListener('change', autocompletarPrecio));
</script>
@endpush
@endsection
