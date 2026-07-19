@extends('layouts.app')
@section('titulo','Editar Caja')
@section('contenido')
<div class="row justify-content-center"><div class="col-lg-6">
<div class="card"><div class="card-header fw-semibold">Editar: {{ $caja->nombre }}</div><div class="card-body">
<form method="POST" action="{{ route('cajas.update',$caja) }}">@csrf @method('PATCH')
<div class="mb-3">
    <label class="form-label fw-semibold">Nombre *</label>
    <input type="text" name="nombre" class="form-control" value="{{ old('nombre',$caja->nombre) }}" required>
</div>
<div class="mb-3"><div class="form-check">
    <input type="checkbox" name="activo" value="1" class="form-check-input" {{ $caja->activo ? 'checked' : '' }}>
    <label class="form-check-label">Caja activa</label>
</div></div>
<div class="d-flex gap-2">
    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Actualizar</button>
    <a href="{{ route('cajas.index') }}" class="btn btn-outline-secondary">Cancelar</a>
</div>
</form>
</div></div>
</div></div>
@endsection
