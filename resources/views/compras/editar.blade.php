@extends('layouts.app')
@section('titulo','Editar Orden de Compra')
@section('contenido')
<div class="row justify-content-center"><div class="col-lg-8">
<div class="card"><div class="card-header fw-semibold">Editar: {{ $pedidoCompra->numero_referencia }}</div>
<div class="card-body">
<form method="POST" action="{{ route('compras.update',$pedidoCompra) }}">@csrf @method('PATCH')
<div class="row g-3">
    <div class="col-md-6"><label class="form-label fw-semibold">Proveedor *</label>
        <select name="proveedor_id" class="form-select" required>
            @foreach($proveedores as $prov)
            <option value="{{ $prov->id }}" @selected($pedidoCompra->proveedor_id == $prov->id)>{{ $prov->nombre }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6"><label class="form-label fw-semibold">Tipo *</label>
        <select name="tipo" class="form-select" required>
            <option value="local" @selected($pedidoCompra->tipo=='local')>Local</option>
            <option value="importada" @selected($pedidoCompra->tipo=='importada')>Importada</option>
        </select>
    </div>
    <div class="col-md-6"><label class="form-label fw-semibold">Almacen</label>
        <select name="ubicacion_id" class="form-select">
            <option value="">-- Sin ubicacion --</option>
            @foreach($ubicaciones as $u)
            <option value="{{ $u->id }}" @selected($pedidoCompra->ubicacion_id == $u->id)>{{ $u->nombre }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6"><label class="form-label fw-semibold">Centro de Costo</label>
        <select name="centro_costo_id" class="form-select">
            <option value="">-- Sin centro de costo --</option>
            @foreach($centrosCosto as $cc)
            <option value="{{ $cc->id }}" @selected($pedidoCompra->centro_costo_id == $cc->id)>{{ $cc->codigo }} — {{ $cc->nombre }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4"><label class="form-label fw-semibold">Fecha Pedido *</label>
        <input type="date" name="fecha_pedido" class="form-control" value="{{ $pedidoCompra->fecha_pedido->format('Y-m-d') }}" required>
    </div>
    <div class="col-md-4"><label class="form-label fw-semibold">Fecha Esperada</label>
        <input type="date" name="fecha_esperada" class="form-control" value="{{ $pedidoCompra->fecha_esperada?->format('Y-m-d') }}">
    </div>
    <div class="col-md-4"><label class="form-label fw-semibold">Estado</label>
        <select name="estado" class="form-select">
            <option value="pendiente"  @selected($pedidoCompra->estado=='pendiente')>Pendiente</option>
            <option value="parcial"    @selected($pedidoCompra->estado=='parcial')>Parcial</option>
            <option value="completado" @selected($pedidoCompra->estado=='completado')>Completado</option>
            <option value="cancelado"  @selected($pedidoCompra->estado=='cancelado')>Cancelado</option>
        </select>
    </div>
    <div class="col-12"><label class="form-label fw-semibold">Comentarios</label>
        <textarea name="comentarios" class="form-control" rows="2">{{ $pedidoCompra->comentarios }}</textarea>
    </div>
</div>
<div class="d-flex gap-2 mt-4">
    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Actualizar</button>
    <a href="{{ route('compras.show',$pedidoCompra) }}" class="btn btn-outline-secondary">Cancelar</a>
</div>
</form>
</div></div>
</div></div>
@endsection
