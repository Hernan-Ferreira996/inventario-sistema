@extends('layouts.app')
@section('titulo','Nuevo Cliente')
@section('contenido')
<div class="row justify-content-center"><div class="col-lg-7">
<div class="card"><div class="card-header">Nuevo Cliente</div><div class="card-body">
<form method="POST" action="{{ route('clientes.store') }}">@csrf
<div class="row g-3">
<div class="col-md-6"><label class="form-label fw-semibold">Nombre *</label><input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}" required>@error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
<div class="col-md-6"><label class="form-label fw-semibold">Email</label><input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">@error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
<div class="col-md-6"><label class="form-label fw-semibold">Telefono</label><input type="text" name="telefono" class="form-control" value="{{ old('telefono') }}"></div>
<div class="col-md-6"><label class="form-label fw-semibold">RUC / NIT</label><input type="text" name="ruc_nit" class="form-control" value="{{ old('ruc_nit') }}"></div>
<div class="col-12"><label class="form-label fw-semibold">Direccion</label><textarea name="direccion" class="form-control" rows="2">{{ old('direccion') }}</textarea></div>
<div class="col-md-6"><label class="form-label fw-semibold">Tipo de Precio *</label>
<select name="tipo_precio" class="form-select"><option value="minorista" {{ old('tipo_precio') == 'minorista' ? 'selected' : '' }}>Minorista</option><option value="mayorista" {{ old('tipo_precio') == 'mayorista' ? 'selected' : '' }}>Mayorista</option></select></div>
<div class="col-md-6"><label class="form-label fw-semibold">Límite de Crédito</label><input type="number" step="0.01" min="0" name="limite_credito" class="form-control" value="{{ old('limite_credito') }}"></div>
<div class="col-12"><label class="form-label fw-semibold">Etiquetas</label><input type="text" name="etiquetas" class="form-control" placeholder="VIP, Mayorista frecuente" value="{{ old('etiquetas') }}"><small class="text-muted">Separadas por coma</small></div>
<x-campos-personalizados :campos="$campos" />
</div>
<div class="d-flex gap-2 mt-3"><button class="btn btn-primary">Guardar</button><a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary">Cancelar</a></div>
</form></div></div>
</div></div>
@endsection