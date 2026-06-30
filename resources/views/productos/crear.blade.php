@extends('layouts.app')
@section('titulo','Nuevo Producto')
@section('contenido')
<div class="row justify-content-center"><div class="col-lg-9">
<div class="card"><div class="card-header fw-semibold">Nuevo Producto</div><div class="card-body">
<form method="POST" action="{{ route('productos.store') }}" enctype="multipart/form-data">@csrf
<div class="row g-3">
<div class="col-md-4"><label class="form-label fw-semibold">Codigo *</label><input type="text" name="codigo" class="form-control @error('codigo') is-invalid @enderror" value="{{ old('codigo') }}" required placeholder="Ej: PROD-001">@error('codigo')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
<div class="col-md-8"><label class="form-label fw-semibold">Nombre *</label><input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}" required>@error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
<div class="col-12"><label class="form-label fw-semibold">Descripcion</label><textarea name="descripcion" class="form-control" rows="2">{{ old('descripcion') }}</textarea></div>
<div class="col-md-4"><label class="form-label fw-semibold">Categoria</label>
<select name="categoria_id" class="form-select"><option value="">-- Sin categoria --</option>@foreach($categorias as $c)<option value="{{ $c->id }}" {{ old('categoria_id') == $c->id ? 'selected' : '' }}>{{ $c->nombre }}</option>@endforeach</select></div>
<div class="col-md-4"><label class="form-label fw-semibold">Unidad</label>
<select name="unidad_id" class="form-select"><option value="">-- Sin unidad --</option>@foreach($unidades as $u)<option value="{{ $u->id }}" {{ old('unidad_id') == $u->id ? 'selected' : '' }}>{{ $u->nombre }}</option>@endforeach</select></div>
<div class="col-md-4"><label class="form-label fw-semibold">Tipo Impuesto</label>
<select name="impuesto_id" class="form-select"><option value="">-- Sin impuesto --</option>@foreach($impuestos as $i)<option value="{{ $i->id }}" {{ old('impuesto_id') == $i->id ? 'selected' : '' }}>{{ $i->nombre }} ({{ $i->porcentaje }}%)</option>@endforeach</select></div>
<div class="col-md-4"><label class="form-label fw-semibold">Precio Compra *</label><div class="input-group"><span class="input-group-text">$</span><input type="number" name="precio_compra" class="form-control" step="0.01" min="0" value="{{ old('precio_compra',0) }}" required></div></div>
<div class="col-md-4"><label class="form-label fw-semibold">Precio Minorista *</label><div class="input-group"><span class="input-group-text">$</span><input type="number" name="precio_venta_minorista" class="form-control" step="0.01" min="0" value="{{ old('precio_venta_minorista',0) }}" required></div></div>
<div class="col-md-4"><label class="form-label fw-semibold">Precio Mayorista *</label><div class="input-group"><span class="input-group-text">$</span><input type="number" name="precio_venta_mayorista" class="form-control" step="0.01" min="0" value="{{ old('precio_venta_mayorista',0) }}" required></div></div>
<div class="col-md-6"><label class="form-label fw-semibold">Imagen</label><input type="file" name="imagen" class="form-control" accept="image/*"></div>
</div>
<div class="d-flex gap-2 mt-4"><button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Guardar Producto</button><a href="{{ route('productos.index') }}" class="btn btn-outline-secondary">Cancelar</a></div>
</form></div></div>
</div></div>
@endsection