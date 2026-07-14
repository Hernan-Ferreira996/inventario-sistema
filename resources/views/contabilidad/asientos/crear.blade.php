@extends('layouts.app')
@section('titulo','Nuevo Asiento Manual')
@section('contenido')
<div class="card">
<div class="card-header fw-semibold">Nuevo Asiento Manual</div>
<div class="card-body">
<form method="POST" action="{{ route('contabilidad.asientos.store') }}">
    @csrf
    <div class="mb-3">
        <label class="form-label fw-semibold">Concepto *</label>
        <input type="text" name="concepto" class="form-control" value="{{ old('concepto') }}" required>
    </div>

    <h6 class="fw-bold mb-2 border-bottom pb-2">Movimientos (el total Debe debe ser igual al total Haber)</h6>
    <div id="lineas-asiento">
    @for($i = 0; $i < 2; $i++)
        <div class="row g-2 mb-2 linea-item align-items-center">
            <div class="col-md-4">
                <select name="lineas[{{ $i }}][cuenta_id]" class="form-select" required>
                    <option value="">-- Cuenta --</option>
                    @foreach($cuentas as $c)
                    <option value="{{ $c->id }}">{{ $c->codigo }} - {{ $c->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3"><input type="text" name="lineas[{{ $i }}][descripcion]" class="form-control" placeholder="Descripción"></div>
            <div class="col-md-2"><div class="input-group"><span class="input-group-text">Debe</span><input type="number" step="0.01" min="0" name="lineas[{{ $i }}][debe]" class="form-control" value="0"></div></div>
            <div class="col-md-2"><div class="input-group"><span class="input-group-text">Haber</span><input type="number" step="0.01" min="0" name="lineas[{{ $i }}][haber]" class="form-control" value="0"></div></div>
            <div class="col-md-1 text-end"><button type="button" class="btn btn-outline-danger btn-sm" onclick="quitarLinea(this)"><i class="bi bi-x"></i></button></div>
        </div>
    @endfor
    </div>
    <button type="button" class="btn btn-outline-success btn-sm mb-3" onclick="agregarLinea()"><i class="bi bi-plus-lg me-1"></i>Agregar línea</button>

    <div class="d-flex gap-2 border-top pt-3">
        <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i>Registrar Asiento</button>
        <a href="{{ route('contabilidad.asientos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
    </div>
</form>
</div>
</div>

@push('scripts')
<script>
let idxAsiento = {{ 2 }};
function agregarLinea() {
    const cont = document.getElementById('lineas-asiento');
    const primera = cont.querySelector('.linea-item');
    const nueva = primera.cloneNode(true);
    nueva.querySelectorAll('input').forEach(i => { i.name = i.name.replace(/\[\d+\]/, `[${idxAsiento}]`); if (i.type === 'number') i.value = 0; else i.value = ''; });
    nueva.querySelectorAll('select').forEach(s => { s.name = s.name.replace(/\[\d+\]/, `[${idxAsiento}]`); s.value = ''; });
    idxAsiento++;
    cont.appendChild(nueva);
}
function quitarLinea(btn) {
    if (document.querySelectorAll('.linea-item').length > 2) btn.closest('.linea-item').remove();
}
</script>
@endpush
@endsection
