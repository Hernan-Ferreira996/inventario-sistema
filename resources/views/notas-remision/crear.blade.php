@extends('layouts.app')
@section('titulo','Nueva Nota de Remisión')
@section('contenido')

@if($config['fact_modo'] === 'local')
<div class="alert alert-warning"><i class="bi bi-exclamation-triangle me-2"></i>Documento en modo demo, sin validez tributaria.</div>
@endif

<div class="card">
    <div class="card-header fw-semibold">Nota de Remisión — Pedido {{ $pedido->numero_referencia }}</div>
    <div class="card-body">
        <form method="POST" action="{{ route('notas-remision.store') }}">
            @csrf
            <input type="hidden" name="pedido_id" value="{{ $pedido->id }}">

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Próximo N° de Documento</label>
                    <input type="text" class="form-control bg-light" value="{{ $config['fact_establecimiento'] }}-{{ $config['fact_punto_expedicion'] }}-{{ $proximoNumero }}" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Motivo *</label>
                    <select name="motivo" class="form-select" required>
                        <option value="venta">Venta</option>
                        <option value="consignacion">Consignación</option>
                        <option value="traslado">Traslado</option>
                        <option value="devolucion">Devolución</option>
                        <option value="otro">Otro</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Almacén de Origen *</label>
                    <select name="ubicacion_origen_id" class="form-select" required>
                        <option value="">-- Seleccionar --</option>
                        @foreach($ubicaciones as $u)
                        <option value="{{ $u->id }}">{{ $u->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Dirección de Destino</label>
                    <input type="text" name="direccion_destino" class="form-control" value="{{ $pedido->direccion_entrega }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Transportista</label>
                    <input type="text" name="transportista" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Placa del Vehículo</label>
                    <input type="text" name="vehiculo_placa" class="form-control" placeholder="ABC 123">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Observaciones</label>
                    <textarea name="observaciones" class="form-control" rows="2"></textarea>
                </div>
                <div class="col-12">
                    <div class="form-check">
                        <input type="checkbox" name="afecta_stock" value="1" class="form-check-input" id="afecta_stock" checked>
                        <label class="form-check-label" for="afecta_stock">Esta remisión afecta el stock (descuenta del almacén de origen)</label>
                    </div>
                </div>
            </div>

            <h6 class="fw-bold mb-2 border-bottom pb-2">Productos a Remitir</h6>
            <table class="table table-sm">
                <thead><tr><th>Producto</th><th class="text-end">Cantidad</th></tr></thead>
                <tbody>
                @foreach($pedido->detalles as $i => $d)
                <tr>
                    <td>
                        <input type="hidden" name="productos[{{ $i }}][producto_id]" value="{{ $d->producto_id }}">
                        {{ $d->producto->nombre ?? '—' }}
                    </td>
                    <td class="text-end" style="width:160px">
                        <input type="number" name="productos[{{ $i }}][cantidad]" class="form-control form-control-sm text-end" step="0.01" min="0.01" value="{{ $d->cantidad }}">
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>

            <div class="d-flex gap-2 border-top pt-3 mt-3">
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-truck me-1"></i>Generar Nota de Remisión</button>
                <a href="{{ route('pedidos.show',$pedido) }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
