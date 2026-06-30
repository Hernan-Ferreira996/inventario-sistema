@extends('layouts.app')
@section('titulo','Nuevo Envío')
@section('contenido')
<div class="card">
    <div class="card-header fw-semibold">Nuevo Envío — Pedido {{ $pedido->numero_referencia }}</div>
    <div class="card-body">
        <form method="POST" action="{{ route('envios.store') }}">
            @csrf
            <input type="hidden" name="pedido_id" value="{{ $pedido->id }}">
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <label class="form-label fw-semibold">N° Envío</label>
                    <input type="text" class="form-control bg-light" value="{{ $proximoNumero }}" readonly>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Fecha de Empaque *</label>
                    <input type="date" name="fecha_empaque" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Fecha de Entrega</label>
                    <input type="date" name="fecha_entrega" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Estado *</label>
                    <select name="estado" class="form-select" required>
                        <option value="preparando">Preparando</option>
                        <option value="enviado">Enviado</option>
                        <option value="entregado">Entregado</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Comentarios</label>
                    <textarea name="comentarios" class="form-control" rows="2"></textarea>
                </div>
            </div>

            <h6 class="fw-bold mb-2 border-bottom pb-2">Productos a Enviar</h6>
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
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-box-seam me-1"></i>Registrar Envío</button>
                <a href="{{ route('pedidos.show',$pedido) }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
