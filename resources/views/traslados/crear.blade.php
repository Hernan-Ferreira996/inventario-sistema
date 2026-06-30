@extends('layouts.app')
@section('titulo','Nuevo Traslado de Stock')
@section('contenido')

<div class="alert alert-info"><i class="bi bi-info-circle me-2"></i>El traslado descuenta el stock del almacén de origen y lo suma al almacén de destino de forma inmediata.</div>

<div class="card">
    <div class="card-header fw-semibold">Nuevo Traslado entre Almacenes</div>
    <div class="card-body">
        <form method="POST" action="{{ route('traslados.store') }}">
            @csrf
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Almacén de Origen *</label>
                    <select name="ubicacion_origen_id" class="form-select" required>
                        <option value="">-- Seleccionar --</option>
                        @foreach($ubicaciones as $u)
                        <option value="{{ $u->id }}">{{ $u->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Almacén de Destino *</label>
                    <select name="ubicacion_destino_id" class="form-select" required>
                        <option value="">-- Seleccionar --</option>
                        @foreach($ubicaciones as $u)
                        <option value="{{ $u->id }}">{{ $u->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Fecha *</label>
                    <input type="date" name="fecha_traslado" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Referencia</label>
                    <input type="text" name="referencia" class="form-control" placeholder="Ej: Reposición tienda">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Notas</label>
                    <textarea name="notas" class="form-control" rows="2"></textarea>
                </div>
            </div>

            <h6 class="fw-bold mb-2 border-bottom pb-2">Productos a Trasladar</h6>
            <div id="lineas-traslado">
                <div class="row g-2 mb-2 linea-item align-items-center">
                    <div class="col-md-8">
                        <select name="productos[0][producto_id]" class="form-select" required>
                            <option value="">-- Seleccionar producto --</option>
                            @foreach($productos as $p)
                            <option value="{{ $p->id }}">{{ $p->codigo }} — {{ $p->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3"><input type="number" name="productos[0][cantidad]" class="form-control" placeholder="Cantidad" step="0.01" min="0.01" required></div>
                    <div class="col-md-1 text-end"><button type="button" class="btn btn-outline-danger btn-sm" onclick="quitarLinea(this)"><i class="bi bi-x"></i></button></div>
                </div>
            </div>
            <button type="button" class="btn btn-outline-success btn-sm mb-4" onclick="agregarLinea()"><i class="bi bi-plus-lg me-1"></i>Agregar producto</button>

            <div class="d-flex gap-2 border-top pt-3">
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-arrow-left-right me-1"></i>Registrar Traslado</button>
                <a href="{{ route('traslados.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@push('scripts')
<script>
let idx = 1;
function agregarLinea() {
    const cont = document.getElementById('lineas-traslado');
    const primera = cont.querySelector('.linea-item');
    const nueva = primera.cloneNode(true);
    nueva.querySelectorAll('input').forEach(i => { i.name = i.name.replace(/\[\d+\]/, `[${idx}]`); i.value=''; });
    nueva.querySelectorAll('select').forEach(s => { s.name = s.name.replace(/\[\d+\]/, `[${idx}]`); s.value=''; });
    idx++;
    cont.appendChild(nueva);
}
function quitarLinea(btn) {
    if (document.querySelectorAll('.linea-item').length > 1) btn.closest('.linea-item').remove();
}
</script>
@endpush
@endsection
