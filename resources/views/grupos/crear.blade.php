@extends('layouts.app')
@section('titulo','Nuevo Grupo de Acceso')
@section('contenido')
<div class="row justify-content-center"><div class="col-lg-5">
<div class="card"><div class="card-header fw-semibold"><i class="bi bi-shield-plus me-2 text-primary"></i>Nuevo Grupo de Acceso</div>
<div class="card-body">
<p class="text-muted small">Los grupos definen qué partes del sistema puede usar un usuario. Podés crear grupos con cualquier nombre (ej: Cajero, Supervisor, Gerente).</p>
<form method="POST" action="{{ route('grupos.store') }}">@csrf
<div class="mb-4">
    <label class="form-label fw-semibold">Nombre del grupo *</label>
    <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
        value="{{ old('nombre') }}" placeholder="Ej: Cajero, Supervisor Ventas..." required autofocus>
    @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="d-flex gap-2">
    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Crear Grupo</button>
    <a href="{{ route('grupos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
</div>
</form>
</div></div>
<div class="alert alert-light border mt-3 small">
    <i class="bi bi-info-circle me-1 text-primary"></i>
    Después de crear el grupo podés configurar exactamente qué módulos y acciones tiene permitidos.
</div>
</div></div>
@endsection
