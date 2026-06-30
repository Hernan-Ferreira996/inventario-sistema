@extends('layouts.app')
@section('titulo','Nuevo Pedido de Venta')
@section('contenido')

<div class="card">
    <div class="card-header fw-semibold">Nuevo Pedido de Venta</div>
    <div class="card-body">
        <form method="POST" action="{{ route('pedidos.store') }}" id="formPedido">
            @csrf
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Número de Referencia</label>
                    <input type="text" class="form-control bg-light" value="{{ $proximoNumero }}" readonly>
                    <input type="hidden" name="numero_referencia" value="{{ $proximoNumero }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Cliente *</label>
                    <select name="cliente_id" class="form-select @error('cliente_id') is-invalid @enderror" required>
                        <option value="">-- Seleccionar cliente --</option>
                        @foreach($clientes as $c)
                        <option value="{{ $c->id }}" {{ old('cliente_id') == $c->id ? 'selected' : '' }}>{{ $c->nombre }}</option>
                        @endforeach
                    </select>
                    @error('cliente_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Ubicación (almacén)</label>
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
                    <label class="form-label fw-semibold">Fecha Entrega</label>
                    <input type="date" name="fecha_entrega" class="form-control" value="{{ old('fecha_entrega') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Término de Pago</label>
                    <select name="termino_pago_id" class="form-select">
                        <option value="">-- Seleccionar --</option>
                        @foreach($terminosPago as $t)
                        <option value="{{ $t->id }}">{{ $t->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Comentarios</label>
                    <textarea name="comentarios" class="form-control" rows="2">{{ old('comentarios') }}</textarea>
                </div>
            </div>

            <h6 class="fw-bold mb-3 border-bottom pb-2">Productos</h6>

            <div id="lineas-pedido">
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
                    <div class="col-md-2"><div class="input-group"><span class="input-group-text">$</span><input type="number" name="productos[0][precio_unitario]" class="form-control precio-input" placeholder="Precio" step="0.01" min="0" value="0" required></div></div>
                    <div class="col-md-2"><div class="input-group"><input type="number" name="productos[0][descuento]" class="form-control" placeholder="Desc%" step="0.01" min="0" max="100" value="0"><span class="input-group-text">%</span></div></div>
                    <div class="col-md-1 text-end"><button type="button" class="btn btn-outline-danger btn-sm btn-quitar" onclick="quitarLinea(this)"><i class="bi bi-x"></i></button></div>
                </div>
            </div>

            <button type="button" class="btn btn-outline-success btn-sm mb-4" onclick="agregarLinea()">
                <i class="bi bi-plus-lg me-1"></i>Agregar producto
            </button>

            <div class="d-flex gap-2 border-top pt-3">
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i>Guardar Pedido</button>
                <a href="{{ route('pedidos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
let idx = 1;
const productosData = {{ Js::from($productos->map(fn($p) => ['id' => $p->id, 'codigo' => $p->codigo, 'nombre' => $p->nombre, 'precio' => $p->precio_venta_minorista])->values()) }};

function agregarLinea() {
    const cont = document.getElementById('lineas-pedido');
    const primera = cont.querySelector('.linea-item');
    const nueva = primera.cloneNode(true);
    nueva.querySelectorAll('input').forEach(i => { if(i.name.includes('cantidad')) i.value = 1; else i.value = 0; });
    nueva.querySelectorAll('select').forEach(s => { s.name = s.name.replace(/\[\d+\]/, `[${idx}]`); s.value = ''; });
    nueva.querySelectorAll('input').forEach(i => { i.name = i.name.replace(/\[\d+\]/, `[${idx}]`); });
    idx++;
    cont.appendChild(nueva);
    nueva.querySelector('.select-producto').addEventListener('change', autocompletarPrecio);
}

function quitarLinea(btn) {
    const lineas = document.querySelectorAll('.linea-item');
    if (lineas.length > 1) btn.closest('.linea-item').remove();
}

function autocompletarPrecio(e) {
    const sel = e.target;
    const opt = sel.options[sel.selectedIndex];
    const precio = opt.getAttribute('data-precio') || 0;
    sel.closest('.linea-item').querySelector('.precio-input').value = precio;
}

document.querySelectorAll('.select-producto').forEach(s => s.addEventListener('change', autocompletarPrecio));
</script>
@endpush
@endsection
