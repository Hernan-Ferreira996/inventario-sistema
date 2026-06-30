@extends('layouts.app')
@section('titulo','Editar Producto')
@section('contenido')
<div class="row justify-content-center"><div class="col-lg-9">
<div class="card"><div class="card-header fw-semibold">Editar: {{ $producto->nombre }}</div><div class="card-body">
<form method="POST" action="{{ route('productos.update',$producto) }}" enctype="multipart/form-data">@csrf @method('PATCH')
<div class="row g-3">
<div class="col-md-4"><label class="form-label fw-semibold">Codigo</label><input type="text" class="form-control bg-light" value="{{ $producto->codigo }}" readonly></div>
<div class="col-md-8"><label class="form-label fw-semibold">Nombre *</label><input type="text" name="nombre" class="form-control" value="{{ old('nombre',$producto->nombre) }}" required></div>
<div class="col-12"><label class="form-label fw-semibold">Descripcion</label><textarea name="descripcion" class="form-control" rows="2">{{ old('descripcion',$producto->descripcion) }}</textarea></div>
<div class="col-md-4"><label class="form-label fw-semibold">Categoria</label>
<select name="categoria_id" class="form-select"><option value="">-- Sin categoria --</option>@foreach($categorias as $c)<option value="{{ $c->id }}" {{ $producto->categoria_id == $c->id ? 'selected' : '' }}>{{ $c->nombre }}</option>@endforeach</select></div>
<div class="col-md-4"><label class="form-label fw-semibold">Unidad</label>
<select name="unidad_id" class="form-select"><option value="">-- Sin unidad --</option>@foreach($unidades as $u)<option value="{{ $u->id }}" {{ $producto->unidad_id == $u->id ? 'selected' : '' }}>{{ $u->nombre }}</option>@endforeach</select></div>
<div class="col-md-4"><label class="form-label fw-semibold">Impuesto</label>
<select name="impuesto_id" class="form-select"><option value="">-- Sin impuesto --</option>@foreach($impuestos as $i)<option value="{{ $i->id }}" {{ $producto->impuesto_id == $i->id ? 'selected' : '' }}>{{ $i->nombre }}</option>@endforeach</select></div>
<div class="col-md-4"><label class="form-label fw-semibold">Precio Compra</label><div class="input-group"><span class="input-group-text">$</span><input type="number" name="precio_compra" class="form-control" step="0.01" value="{{ $producto->precio_compra }}" required></div></div>
<div class="col-md-4"><label class="form-label fw-semibold">Precio Minorista</label><div class="input-group"><span class="input-group-text">$</span><input type="number" name="precio_venta_minorista" class="form-control" step="0.01" value="{{ $producto->precio_venta_minorista }}" required></div></div>
<div class="col-md-4"><label class="form-label fw-semibold">Precio Mayorista</label><div class="input-group"><span class="input-group-text">$</span><input type="number" name="precio_venta_mayorista" class="form-control" step="0.01" value="{{ $producto->precio_venta_mayorista }}" required></div></div>
<div class="col-md-6"><label class="form-label fw-semibold">Nueva Imagen</label><input type="file" name="imagen" class="form-control" accept="image/*">@if($producto->imagen)<small class="text-muted">Imagen actual: {{ basename($producto->imagen) }}</small>@endif</div>
<div class="col-md-6 d-flex align-items-end"><div class="form-check mb-1"><input type="checkbox" name="activo" value="1" class="form-check-input" {{ $producto->activo ? 'checked' : '' }}><label class="form-check-label">Producto activo</label></div></div>
</div>
<div class="d-flex gap-2 mt-4"><button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Actualizar</button><a href="{{ route('productos.show',$producto) }}" class="btn btn-outline-secondary">Cancelar</a></div>
</form></div></div>
</div></div>
@endsection