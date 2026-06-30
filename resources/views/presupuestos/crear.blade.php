@extends('layouts.app')
@section('titulo','Nuevo Presupuesto')
@section('contenido')

<div class="card">
    <div class="card-header fw-semibold">Nuevo Presupuesto</div>
    <div class="card-body">
        <form method="POST" action="{{ route('presupuestos.store') }}">
            @csrf
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">N° de Documento</label>
                    <input type="text" class="form-control bg-light" value="{{ $proximoNumero }}" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Cliente *</label>
                    <select name="cliente_id" class="form-select" required>
                        <option value="">-- Seleccionar cliente --</option>
                        @foreach($clientes as $c)
                        <option value="{{ $c->id }}">{{ $c->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Válido Hasta</label>
                    <input type="date" name="fecha_validez" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Fecha de Emisión *</label>
                    <input type="date" name="fecha_emision" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Comentarios</label>
                    <textarea name="comentarios" class="form-control" rows="2"></textarea>
                </div>
            </div>

            <h6 class="fw-bold mb-3 border-bottom pb-2">Productos</h6>
            <div id="lineas-presupuesto">
                <div class="row g-2 mb-2 linea-item align-items-center">
                    <div class="col-md-5">
                        <select name="productos[0][producto_id]" class="form-select select-producto" required>
                            <option value="">-- Seleccionar producto --</option>
                            @foreach($productos as $p)
                            <option value="{{ $p->id }}" data-precio="{{ $p->precio_venta_minorista }}">{{ $p->codigo }} — {{ $p->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2"><input type="number" name="productos[0][cantidad]" class="form-control" placeholder="Cantidad" step="0.01" min="0.01" value="1" required></div>
                    <div class="col-md-2"><div class="input-group"><span class="input-group-text">Gs.</span><input type="number" name="productos[0][precio_unitario]" class="form-control precio-input" step="0.01" min="0" value="0" required></div></div>
                    <div class="col-md-2"><div class="input-group"><input type="number" name="productos[0][descuento]" class="form-control" step="0.01" min="0" max="100" value="0"><span class="input-group-text">%</span></div></div>
                    <div class="col-md-1 text-end"><button type="button" class="btn btn-outline-danger btn-sm" onclick="quitarLinea(this)"><i class="bi bi-x"></i></button></div>
                </div>
            </div>
            <button type="button" class="btn btn-outline-success btn-sm mb-4" onclick="agregarLinea()"><i class="bi bi-plus-lg me-1"></i>Agregar producto</button>

            <div class="d-flex gap-2 border-top pt-3">
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i>Guardar Presupuesto</button>
                <a href="{{ route('presupuestos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
let idx = 1;
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
