@extends('layouts.app')
@section('titulo','Nueva Ciudad')
@section('contenido')
<div class="row justify-content-center"><div class="col-lg-6">
<div class="card"><div class="card-header fw-semibold">Nueva Ciudad</div><div class="card-body">
<form method="POST" action="{{ route('ciudades.store') }}">@csrf
<div class="mb-3">
    <label class="form-label fw-semibold">Nombre *</label>
    <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}" required>
    @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="mb-3">
    <label class="form-label fw-semibold">Departamento</label>
    <input type="text" name="departamento" class="form-control" value="{{ old('departamento') }}">
</div>
<div class="mb-3">
    <label class="form-label fw-semibold">País *</label>
    <input type="text" name="pais" class="form-control" value="{{ old('pais','Paraguay') }}" required>
</div>
<div class="d-flex gap-2">
    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Guardar</button>
    <a href="{{ route('ciudades.index') }}" class="btn btn-outline-secondary">Cancelar</a>
</div>
</form>
</div></div>
</div></div>
@endsection
