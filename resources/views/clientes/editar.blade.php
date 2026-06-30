@extends('layouts.app')
@section('titulo','Editar Cliente')
@section('contenido')
<div class="row justify-content-center"><div class="col-lg-7">
<div class="card"><div class="card-header">Editar: {{ $cliente->nombre }}</div><div class="card-body">
<form method="POST" action="{{ route('clientes.update',$cliente) }}">@csrf @method('PATCH')
<div class="row g-3">
<div class="col-md-6"><label class="form-label fw-semibold">Nombre *</label><input type="text" name="nombre" class="form-control" value="{{ old('nombre',$cliente->nombre) }}" required></div>
<div class="col-md-6"><label class="form-label fw-semibold">Email</label><input type="email" name="email" class="form-control" value="{{ old('email',$cliente->email) }}"></div>
<div class="col-md-6"><label class="form-label fw-semibold">Telefono</label><input type="text" name="telefono" class="form-control" value="{{ old('telefono',$cliente->telefono) }}"></div>
<div class="col-md-6"><label class="form-label fw-semibold">RUC/NIT</label><input type="text" name="ruc_nit" class="form-control" value="{{ old('ruc_nit',$cliente->ruc_nit) }}"></div>
<div class="col-12"><label class="form-label fw-semibold">Direccion</label><textarea name="direccion" class="form-control" rows="2">{{ old('direccion',$cliente->direccion) }}</textarea></div>
<div class="col-md-6"><label class="form-label fw-semibold">Tipo Precio</label>
<select name="tipo_precio" class="form-select"><option value="minorista" {{ $cliente->tipo_precio == 'minorista' ? 'selected' : '' }}>Minorista</option><option value="mayorista" {{ $cliente->tipo_precio == 'mayorista' ? 'selected' : '' }}>Mayorista</option></select></div>
<div class="col-md-6"><div class="form-check mt-4"><input type="checkbox" name="activo" value="1" class="form-check-input" {{ $cliente->activo ? 'checked' : '' }}><label class="form-check-label">Cliente activo</label></div></div>
</div>
<div class="d-flex gap-2 mt-3"><button class="btn btn-primary">Actualizar</button><a href="{{ route('clientes.show',$cliente) }}" class="btn btn-outline-secondary">Cancelar</a></div>
</form></div></div>
</div></div>
@endsection