@extends('layouts.app')
@section('titulo','Nuevo Proveedor')
@section('contenido')
<div class="row justify-content-center"><div class="col-lg-8">
<div class="card"><div class="card-header fw-semibold">Nuevo Proveedor</div><div class="card-body">
<form method="POST" action="{{ route('proveedores.store') }}">@csrf
<div class="row g-3">
<div class="col-md-8"><label class="form-label fw-semibold">Nombre *</label>
    <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}" required>
    @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
<div class="col-md-4"><label class="form-label fw-semibold">RUC / NIT</label>
    <input type="text" name="ruc_nit" class="form-control" value="{{ old('ruc_nit') }}"></div>
<div class="col-md-6"><label class="form-label fw-semibold">Persona de Contacto</label>
    <input type="text" name="contacto" class="form-control" value="{{ old('contacto') }}"></div>
<div class="col-md-6"><label class="form-label fw-semibold">Teléfono</label>
    <input type="text" name="telefono" class="form-control" value="{{ old('telefono') }}"></div>
<div class="col-md-6"><label class="form-label fw-semibold">Email</label>
    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
<div class="col-12"><label class="form-label fw-semibold">Dirección</label>
    <input type="text" name="direccion" class="form-control" value="{{ old('direccion') }}"></div>
<div class="col-md-6"><label class="form-label fw-semibold">País</label>
    <input type="text" name="pais" class="form-control" value="{{ old('pais', 'Paraguay') }}"></div>
<div class="col-md-3"><div class="form-check mt-4"><input type="checkbox" name="expuesto_publicamente" value="1" class="form-check-input" {{ old('expuesto_publicamente') ? 'checked' : '' }}><label class="form-check-label">PEP</label></div></div>
<div class="col-md-3"><div class="form-check mt-4"><input type="checkbox" name="funcionario" value="1" class="form-check-input" {{ old('funcionario') ? 'checked' : '' }}><label class="form-check-label">Funcionario público</label></div></div>
<div class="col-12"><label class="form-label fw-semibold">Etiquetas</label><input type="text" name="etiquetas" class="form-control" placeholder="Confiable, Entrega rápida" value="{{ old('etiquetas') }}"><small class="text-muted">Separadas por coma</small></div>
<x-campos-personalizados :campos="$campos" />
</div>
<div class="d-flex gap-2 mt-4">
    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Guardar</button>
    <a href="{{ route('proveedores.index') }}" class="btn btn-outline-secondary">Cancelar</a>
</div>
</form></div></div>
</div></div>
@endsection
