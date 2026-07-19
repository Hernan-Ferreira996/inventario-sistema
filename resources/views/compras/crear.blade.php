@extends('layouts.app')
@section('titulo','Nueva Orden de Compra')
@section('contenido')
<div class="card">
    <div class="card-header fw-semibold">Nueva Orden de Compra</div>
    <div class="card-body">
        <form method="POST" action="{{ route('compras.store') }}">
            @csrf
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Nº Referencia</label>
                    <input type="text" class="form-control bg-light" value="{{ $proximoNumero }}" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Proveedor *</label>
                    <select name="proveedor_id" class="form-select @error('proveedor_id') is-invalid @enderror" required>
                        <option value="">-- Seleccionar proveedor --</option>
                        @foreach($proveedores as $prov)
                        <option value="{{ $prov->id }}" {{ old('proveedor_id') == $prov->id ? 'selected' : '' }}>{{ $prov->nombre }}</option>
                        @endforeach
                    </select>
                    @error('proveedor_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tipo *</label>
                    <select name="tipo" class="form-select" required>
                        <option value="local" {{ old('tipo','local') == 'local' ? 'selected' : '' }}>Local</option>
                        <option value="importada" {{ old('tipo') == 'importada' ? 'selected' : '' }}>Importada</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Almacén destino</label>
                    <select name="ubicacion_id" class="form-select">
                        <option value="">-- Sin ubicación --</option>
                        @foreach($ubicaciones as $u)
                        <option value="{{ $u->id }}" {{ old('ubicacion_id') == $u->id ? 'selected' : '' }}>{{ $u->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Fecha Pedido *</label>
                    <input type="date" name="fecha_pedido" class="form-control" value="{{ old('fecha_pedido', date('Y-m-d')) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Fecha Esperada</label>
                    <input type="date" name="fecha_esperada" class="form-control" value="{{ old('fecha_esperada') }}">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Comentarios</label>
                    <textarea name="comentarios" class="form-control" rows="2">{{ old('comentarios') }}</textarea>
                </div>
            </div>

            <h6 class="fw-bold mb-3 border-bottom pb-2">Productos a Comprar</h6>
            <div id="lineas-compra">
                <div class="row g-2 mb-2 linea-item align-items-center">
                    <div class="col-md-5">
                        <select name="productos[0][producto_id]" class="form-select select-producto" required>
                            <option value="">-- Seleccionar producto --</option>
                            @foreach($productos as $prod)
                            <option value="{{ $prod->id }}" data-precio="{{ $prod->precio_compra }}">
                                {{ $prod->codigo }} — {{ $prod->nombre }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="productos[0][cantidad]" class="form-control" placeholder="Cantidad" step="0.01" min="0.01" value="1" required>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" name="productos[0][precio_unitario]" class="form-control precio-input" placeholder="Precio compra" step="0.01" min="0" value="0" required>
                        </div>
                    </div>
                    <div class="col-md-1 text-end">
                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="quitarLinea(this)"><i class="bi bi-x"></i></button>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-outline-success btn-sm mb-4" onclick="agregarLinea()">
                <i class="bi bi-plus-lg me-1"></i>Agregar producto
            </button>

            <div class="d-flex gap-2 border-top pt-3">
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i>Crear Orden</button>
                <a href="{{ route('compras.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@push('scripts')
<script>
let idx = 1;
function agregarLinea() {
    const cont = document.getElementById('lineas-compra');
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
