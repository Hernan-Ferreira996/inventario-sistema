@extends('layouts.app')
@section('titulo','Nuevo Centro de Costo')
@section('contenido')
<div class="row justify-content-center"><div class="col-lg-6">
<div class="card"><div class="card-header fw-semibold">Nuevo Centro de Costo</div><div class="card-body">
<form method="POST" action="{{ route('centros-costo.store') }}">@csrf
<div class="mb-3">
    <label class="form-label fw-semibold">Código *</label>
    <input type="text" name="codigo" class="form-control @error('codigo') is-invalid @enderror" value="{{ old('codigo') }}" placeholder="ADM, VEN, LOG..." required>
    @error('codigo')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="mb-3">
    <label class="form-label fw-semibold">Nombre *</label>
    <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}" required>
    @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="mb-3">
    <label class="form-label fw-semibold">Descripción</label>
    <textarea name="descripcion" class="form-control" rows="3">{{ old('descripcion') }}</textarea>
</div>
<div class="d-flex gap-2">
    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Guardar</button>
    <a href="{{ route('centros-costo.index') }}" class="btn btn-outline-secondary">Cancelar</a>
</div>
</form>
</div></div>
</div></div>
@endsection
