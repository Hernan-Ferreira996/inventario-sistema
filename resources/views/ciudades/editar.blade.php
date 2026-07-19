@extends('layouts.app')
@section('titulo','Editar Ciudad')
@section('contenido')
<div class="row justify-content-center"><div class="col-lg-6">
<div class="card"><div class="card-header fw-semibold">Editar: {{ $ciudad->nombre }}</div><div class="card-body">
<form method="POST" action="{{ route('ciudades.update',$ciudad) }}">@csrf @method('PATCH')
<div class="mb-3">
    <label class="form-label fw-semibold">Nombre *</label>
    <input type="text" name="nombre" class="form-control" value="{{ old('nombre',$ciudad->nombre) }}" required>
</div>
<div class="mb-3">
    <label class="form-label fw-semibold">Departamento</label>
    <input type="text" name="departamento" class="form-control" value="{{ old('departamento',$ciudad->departamento) }}">
</div>
<div class="mb-3">
    <label class="form-label fw-semibold">País *</label>
    <input type="text" name="pais" class="form-control" value="{{ old('pais',$ciudad->pais) }}" required>
</div>
<div class="mb-3"><div class="form-check">
    <input type="checkbox" name="activo" value="1" class="form-check-input" {{ $ciudad->activo ? 'checked' : '' }}>
    <label class="form-check-label">Ciudad activa</label>
</div></div>
<div class="d-flex gap-2">
    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Actualizar</button>
    <a href="{{ route('ciudades.index') }}" class="btn btn-outline-secondary">Cancelar</a>
</div>
</form>
</div></div>
</div></div>
@endsection
