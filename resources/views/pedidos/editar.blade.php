@extends('layouts.app')
@section('titulo','Editar Pedido de Venta')
@section('contenido')

<div class="card">
    <div class="card-header fw-semibold">Editar Pedido: {{ $pedido->numero_referencia }}</div>
    <div class="card-body">
        <form method="POST" action="{{ route('pedidos.update',$pedido) }}" id="formPedido">
            @csrf @method('PATCH')
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Numero de Referencia</label>
                    <input type="text" class="form-control bg-light" value="{{ $pedido->numero_referencia }}" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Cliente *</label>
                    <select name="cliente_id" class="form-select" required>
                        <option value="">-- Seleccionar cliente --</option>
                        @foreach($clientes as $c)
                        <option value="{{ $c->id }}" {{ $pedido->cliente_id == $c->id ? 'selected' : '' }}>{{ $c->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Ubicacion (almacen)</label>
                    <select name="ubicacion_id" class="form-select">
                        <option value="">-- Sin ubicacion --</option>
                        @foreach($ubicaciones as $u)
                        <option value="{{ $u->id }}" {{ $pedido->ubicacion_id == $u->id ? 'selected' : '' }}>{{ $u->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Fecha Pedido *</label>
                    <input type="date" name="fecha_pedido" class="form-control" value="{{ $pedido->fecha_pedido->format('Y-m-d') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Fecha Entrega</label>
                    <input type="date" name="fecha_entrega" class="form-control" value="{{ $pedido->fecha_entrega?->format('Y-m-d') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Termino de Pago</label>
                    <select name="termino_pago_id" class="form-select">
                        <option value="">-- Seleccionar --</option>
                        @foreach($terminosPago as $t)
                        <option value="{{ $t->id }}" {{ $pedido->termino_pago_id == $t->id ? 'selected' : '' }}>{{ $t->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Comentarios</label>
                    <textarea name="comentarios" class="form-control" rows="2">{{ $pedido->comentarios }}</textarea>
                </div>
            </div>

            <h6 class="fw-bold mb-3 border-bottom pb-2">Productos</h6>

            <div id="lineas-pedido">
                @foreach($pedido->detalles as $i => $d)
                <div class="row g-2 mb-2 linea-item align-items-center">
                    <div class="col-md-5">
                        <select name="productos[{{ $i }}][producto_id]" class="form-select select-producto" required>
                            <option value="">-- Seleccionar producto --</option>
                            @foreach($productos as $p)
                            <option value="{{ $p->id }}" data-precio="{{ $p->precio_venta_minorista }}" {{ $d->producto_id == $p->id ? 'selected' : '' }}>{{ $p->codigo }} — {{ $p->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2"><input type="number" name="productos[{{ $i }}][cantidad]" class="form-control" placeholder="Cantidad" step="0.01" min="0.01" value="{{ $d->cantidad }}" required></div>
                    <div class="col-md-2"><div class="input-group"><span class="input-group-text">$</span><input type="number" name="productos[{{ $i }}][precio_unitario]" class="form-control precio-input" placeholder="Precio" step="0.01" min="0" value="{{ $d->precio_unitario }}" required></div></div>
                    <div class="col-md-2"><div class="input-group"><input type="number" name="productos[{{ $i }}][descuento]" class="form-control" placeholder="Desc%" step="0.01" min="0" max="100" value="{{ $d->descuento }}"><span class="input-group-text">%</span></div></div>
                    <div class="col-md-1 text-end"><button type="button" class="btn btn-outline-danger btn-sm" onclick="quitarLinea(this)"><i class="bi bi-x"></i></button></div>
                </div>
                @endforeach
            </div>

            <button type="button" class="btn btn-outline-success btn-sm mb-4" onclick="agregarLinea()">
                <i class="bi bi-plus-lg me-1"></i>Agregar producto
            </button>

            <div class="d-flex gap-2 border-top pt-3">
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i>Actualizar Pedido</button>
                <a href="{{ route('pedidos.show',$pedido) }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
let idx = {{ $pedido->detalles->count() }};
function agregarLinea() {
    const cont = document.getElementById('lineas-pedido');
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
