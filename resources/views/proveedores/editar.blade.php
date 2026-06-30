@extends('layouts.app')
@section('titulo','Editar Proveedor')
@section('contenido')
<div class="row justify-content-center"><div class="col-lg-8">
<div class="card"><div class="card-header fw-semibold">Editar: {{ $proveedor->nombre }}</div><div class="card-body">
<form method="POST" action="{{ route('proveedores.update',$proveedor) }}">@csrf @method('PATCH')
<div class="row g-3">
<div class="col-md-8"><label class="form-label fw-semibold">Nombre *</label>
    <input type="text" name="nombre" class="form-control" value="{{ old('nombre',$proveedor->nombre) }}" required></div>
<div class="col-md-4"><label class="form-label fw-semibold">RUC / NIT</label>
    <input type="text" name="ruc_nit" class="form-control" value="{{ old('ruc_nit',$proveedor->ruc_nit) }}"></div>
<div class="col-md-6"><label class="form-label fw-semibold">Contacto</label>
    <input type="text" name="contacto" class="form-control" value="{{ old('contacto',$proveedor->contacto) }}"></div>
<div class="col-md-6"><label class="form-label fw-semibold">Teléfono</label>
    <input type="text" name="telefono" class="form-control" value="{{ old('telefono',$proveedor->telefono) }}"></div>
<div class="col-md-6"><label class="form-label fw-semibold">Email</label>
    <input type="email" name="email" class="form-control" value="{{ old('email',$proveedor->email) }}"></div>
<div class="col-12"><label class="form-label fw-semibold">Dirección</label>
    <input type="text" name="direccion" class="form-control" value="{{ old('direccion',$proveedor->direccion) }}"></div>
<div class="col-12"><div class="form-check">
    <input type="checkbox" name="activo" value="1" class="form-check-input" {{ $proveedor->activo ? 'checked' : '' }}>
    <label class="form-check-label">Proveedor activo</label>
</div></div>
</div>
<div class="d-flex gap-2 mt-4">
    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Actualizar</button>
    <a href="{{ route('proveedores.show',$proveedor) }}" class="btn btn-outline-secondary">Cancelar</a>
</div>
</form></div></div>
</div></div>
@endsection
