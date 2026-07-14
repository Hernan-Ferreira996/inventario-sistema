@extends('layouts.app')
@section('titulo','Editar Presupuesto')
@section('contenido')

<div class="card">
    <div class="card-header fw-semibold">Editar: {{ $presupuesto->numero_documento }}</div>
    <div class="card-body">
        <form method="POST" action="{{ route('presupuestos.update',$presupuesto) }}" id="formPresupuesto">
            @csrf @method('PATCH')
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Cliente *</label>
                    <select name="cliente_id" class="form-select" required>
                        @foreach($clientes as $c)
                        <option value="{{ $c->id }}" {{ $presupuesto->cliente_id == $c->id ? 'selected' : '' }}>{{ $c->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Fecha de Emisión *</label>
                    <input type="date" name="fecha_emision" class="form-control" value="{{ $presupuesto->fecha_emision->format('Y-m-d') }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Válido Hasta</label>
                    <input type="date" name="fecha_validez" class="form-control" value="{{ $presupuesto->fecha_validez?->format('Y-m-d') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Estado</label>
                    <select name="estado" class="form-select">
                        @foreach(array_diff(\App\Models\CatalogoValor::codigos('presupuestos.estado'), ['convertido']) as $e)
                        <option value="{{ $e }}" {{ $presupuesto->estado === $e ? 'selected' : '' }}>{{ \App\Models\CatalogoValor::etiqueta('presupuestos.estado', $e) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Etapa del Pipeline</label>
                    <select name="etapa" class="form-select">
                        @foreach(\App\Models\CatalogoValor::codigos('presupuestos.etapa') as $e)
                        <option value="{{ $e }}" {{ $presupuesto->etapa === $e ? 'selected' : '' }}>{{ \App\Models\CatalogoValor::etiqueta('presupuestos.etapa', $e) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Comentarios</label>
                    <textarea name="comentarios" class="form-control" rows="2">{{ $presupuesto->comentarios }}</textarea>
                </div>
            </div>

            <h6 class="fw-bold mb-3 border-bottom pb-2">Productos</h6>
            <div id="lineas-presupuesto">
            @foreach($presupuesto->detalles as $i => $d)
                <div class="row g-2 mb-2 linea-item align-items-center">
                    <div class="col-md-5">
                        <select name="productos[{{ $i }}][producto_id]" class="form-select select-producto" required>
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
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i>Actualizar</button>
                <a href="{{ route('presupuestos.show',$presupuesto) }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
let idx = {{ $presupuesto->detalles->count() }};
function agregarLinea() {
    const cont = document.getElementById('lineas-presupuesto');
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
