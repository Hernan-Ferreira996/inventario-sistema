@extends('layouts.app')
@section('titulo','Editar Factura de Proveedor')
@section('contenido')
@php
$cantidadCuotasActual = $facturaProveedor->cuotas->count() ?: 1;
$diasEntreCuotasActual = $facturaProveedor->cuotas->count() > 1
    ? $facturaProveedor->cuotas[1]->fecha_vencimiento->diffInDays($facturaProveedor->cuotas[0]->fecha_vencimiento)
    : 30;
@endphp
<div class="card">
    <div class="card-header fw-semibold">Editar: {{ $facturaProveedor->numero_referencia }}</div>
    <div class="card-body">
        <form method="POST" action="{{ route('facturas-proveedor.update',$facturaProveedor) }}">
            @csrf @method('PATCH')
            <h6 class="fw-bold mb-2 border-bottom pb-2">Datos de la Factura</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Proveedor *</label>
                    <select name="proveedor_id" class="form-select" required>
                        @foreach($proveedores as $prov)
                        <option value="{{ $prov->id }}" @selected($facturaProveedor->proveedor_id == $prov->id)>{{ $prov->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Centro de Costo</label>
                    <select name="centro_costo_id" class="form-select">
                        <option value="">-- Sin centro de costo --</option>
                        @foreach($centrosCosto as $cc)
                        <option value="{{ $cc->id }}" @selected($facturaProveedor->centro_costo_id == $cc->id)>{{ $cc->codigo }} — {{ $cc->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">N° Factura del Proveedor *</label>
                    <input type="text" name="numero_factura_proveedor" class="form-control" value="{{ old('numero_factura_proveedor',$facturaProveedor->numero_factura_proveedor) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Timbrado del Proveedor</label>
                    <input type="text" name="timbrado_proveedor" class="form-control" value="{{ old('timbrado_proveedor',$facturaProveedor->timbrado_proveedor) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">RUC del Proveedor</label>
                    <input type="text" name="ruc_proveedor" class="form-control" value="{{ old('ruc_proveedor',$facturaProveedor->ruc_proveedor) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Fecha de Emisión *</label>
                    <input type="date" name="fecha_emision" class="form-control" value="{{ old('fecha_emision',$facturaProveedor->fecha_emision->format('Y-m-d')) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Fecha de Vencimiento</label>
                    <input type="date" name="fecha_vencimiento" class="form-control" value="{{ old('fecha_vencimiento',$facturaProveedor->fecha_vencimiento?->format('Y-m-d')) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">IVA (%) *</label>
                    @php $ivaActual = $facturaProveedor->subtotal > 0 ? round(($facturaProveedor->iva_total / $facturaProveedor->subtotal) * 100) : 10; @endphp
                    <select name="iva_porcentaje" class="form-select" required>
                        <option value="10" @selected(old('iva_porcentaje',$ivaActual) == 10)>10%</option>
                        <option value="5" @selected(old('iva_porcentaje',$ivaActual) == 5)>5%</option>
                        <option value="0" @selected(old('iva_porcentaje',$ivaActual) == 0)>Exento (0%)</option>
                    </select>
                </div>
            </div>

            <h6 class="fw-bold mb-3 border-bottom pb-2">Conceptos / Gastos</h6>
            <div id="lineas-factura">
                @foreach($facturaProveedor->detalles as $i => $d)
                <div class="row g-2 mb-2 linea-item align-items-center">
                    <div class="col-md-4">
                        <input type="text" name="lineas[{{ $i }}][concepto]" class="form-control" value="{{ $d->concepto }}" required>
                    </div>
                    <div class="col-md-3">
                        <select name="lineas[{{ $i }}][centro_costo_id]" class="form-select">
                            <option value="">-- Sin centro de costo --</option>
                            @foreach($centrosCosto as $cc)
                            <option value="{{ $cc->id }}" @selected($d->centro_costo_id == $cc->id)>{{ $cc->codigo }} — {{ $cc->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="lineas[{{ $i }}][cantidad]" class="form-control" step="0.01" min="0.01" value="{{ $d->cantidad }}" required>
                    </div>
                    <div class="col-md-2">
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" name="lineas[{{ $i }}][precio_unitario]" class="form-control" step="0.01" min="0" value="{{ $d->precio_unitario }}" required>
                        </div>
                    </div>
                    <div class="col-md-1 text-end">
                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="quitarLinea(this)"><i class="bi bi-x"></i></button>
                    </div>
                </div>
                @endforeach
            </div>
            <button type="button" class="btn btn-outline-success btn-sm mb-4" onclick="agregarLinea()">
                <i class="bi bi-plus-lg me-1"></i>Agregar concepto
            </button>

            <h6 class="fw-bold mb-3 border-bottom pb-2">Retención de IVA</h6>
            <div class="row g-3 mb-4">
                <div class="col-12">
                    <div class="form-check">
                        <input type="checkbox" name="retiene_iva" value="1" class="form-check-input" id="retiene_iva" {{ old('retiene_iva',$facturaProveedor->retiene_iva) ? 'checked' : '' }}>
                        <label class="form-check-label" for="retiene_iva">Esta factura tiene retención de IVA</label>
                    </div>
                </div>
                <div id="campos-retencion" class="col-12 row g-3" style="display:none">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Timbrado Retención</label>
                        <input type="text" name="retencion_timbrado" class="form-control" value="{{ old('retencion_timbrado',$facturaProveedor->retencion_timbrado) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">N° Comprobante Retención</label>
                        <input type="text" name="retencion_numero" class="form-control" value="{{ old('retencion_numero',$facturaProveedor->retencion_numero) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">% Retenido</label>
                        <input type="number" name="retencion_porcentaje" class="form-control" step="0.01" min="0" max="100" value="{{ old('retencion_porcentaje',$facturaProveedor->retencion_porcentaje) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Monto Retenido</label>
                        <input type="number" name="retencion_monto" class="form-control" step="0.01" min="0" value="{{ old('retencion_monto',$facturaProveedor->retencion_monto) }}">
                    </div>
                </div>
            </div>

            <h6 class="fw-bold mb-3 border-bottom pb-2">Cuotas</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Cantidad de Cuotas *</label>
                    <input type="number" name="cantidad_cuotas" class="form-control" min="1" max="36" value="{{ old('cantidad_cuotas',$cantidadCuotasActual) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Días entre Cuotas *</label>
                    <input type="number" name="dias_entre_cuotas" class="form-control" min="1" max="365" value="{{ old('dias_entre_cuotas',$diasEntreCuotasActual) }}" required>
                    <small class="text-muted">Al guardar se regeneran las cuotas desde cero.</small>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Observaciones</label>
                <textarea name="observaciones" class="form-control" rows="2">{{ old('observaciones',$facturaProveedor->observaciones) }}</textarea>
            </div>

            <div class="d-flex gap-2 border-top pt-3">
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i>Actualizar</button>
                <a href="{{ route('facturas-proveedor.show',$facturaProveedor) }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@push('scripts')
<script>
let idxLinea = {{ $facturaProveedor->detalles->count() }};
function agregarLinea() {
    const cont = document.getElementById('lineas-factura');
    const primera = cont.querySelector('.linea-item');
    const nueva = primera.cloneNode(true);
    nueva.querySelectorAll('input').forEach(i => { i.name = i.name.replace(/\[\d+\]/, `[${idxLinea}]`); if(i.name.includes('cantidad')) i.value=1; else if(i.type==='number') i.value=0; else i.value=''; });
    nueva.querySelectorAll('select').forEach(s => { s.name = s.name.replace(/\[\d+\]/, `[${idxLinea}]`); s.value=''; });
    idxLinea++;
    cont.appendChild(nueva);
}
function quitarLinea(btn) {
    if (document.querySelectorAll('.linea-item').length > 1) btn.closest('.linea-item').remove();
}
document.getElementById('retiene_iva').addEventListener('change', function() {
    document.getElementById('campos-retencion').style.display = this.checked ? 'flex' : 'none';
});
if (document.getElementById('retiene_iva').checked) {
    document.getElementById('campos-retencion').style.display = 'flex';
}
</script>
@endpush
@endsection
