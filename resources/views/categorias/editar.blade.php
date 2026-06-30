@extends('layouts.app')
@section('titulo','Editar Categoria')
@section('contenido')
<div class="row justify-content-center"><div class="col-lg-6">
<div class="card"><div class="card-header fw-semibold">Editar: {{ $categoria->nombre }}</div><div class="card-body">
<form method="POST" action="{{ route('categorias.update',$categoria) }}">@csrf @method('PATCH')
<div class="mb-3">
    <label class="form-label fw-semibold">Nombre *</label>
    <input type="text" name="nombre" class="form-control" value="{{ old('nombre',$categoria->nombre) }}" required>
</div>
<div class="mb-3">
    <label class="form-label fw-semibold">Descripcion</label>
    <textarea name="descripcion" class="form-control" rows="3">{{ old('descripcion',$categoria->descripcion) }}</textarea>
</div>
<div class="mb-3"><div class="form-check">
    <input type="checkbox" name="activo" value="1" class="form-check-input" {{ $categoria->activo ? 'checked' : '' }}>
    <label class="form-check-label">Categoria activa</label>
</div></div>
<div class="d-flex gap-2">
    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Actualizar</button>
    <a href="{{ route('categorias.index') }}" class="btn btn-outline-secondary">Cancelar</a>
</div>
</form>
</div></div>
</div></div>
@endsection
