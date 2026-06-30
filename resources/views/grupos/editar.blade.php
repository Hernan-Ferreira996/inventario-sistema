@extends('layouts.app')
@section('titulo','Editar Grupo')
@section('contenido')
<div class="row justify-content-center"><div class="col-lg-5">
<div class="card"><div class="card-header fw-semibold">Editar Grupo: <span class="text-primary">{{ $grupo->name }}</span></div>
<div class="card-body">
<form method="POST" action="{{ route('grupos.update',$grupo) }}">@csrf @method('PATCH')
<div class="mb-4">
    <label class="form-label fw-semibold">Nombre del grupo *</label>
    <input type="text" name="nombre" class="form-control {{ $grupo->name === 'admin' ? 'bg-light' : '' }}"
        value="{{ old('nombre',$grupo->name) }}" {{ $grupo->name === 'admin' ? 'readonly' : '' }} required>
    @if($grupo->name === 'admin')
    <small class="text-muted">El grupo admin no puede renombrarse.</small>
    @endif
</div>
<div class="d-flex gap-2">
    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Actualizar</button>
    <a href="{{ route('grupos.show',$grupo) }}" class="btn btn-outline-secondary">Cancelar</a>
</div>
</form>
</div></div>
</div></div>
@endsection
