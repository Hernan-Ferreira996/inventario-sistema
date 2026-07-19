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
<div class="col-md-6"><label class="form-label fw-semibold">Ciudad</label>
    <select name="ciudad_id" class="form-select">
    <option value="">-- Sin ciudad --</option>
    @foreach($ciudades as $ciu)
    <option value="{{ $ciu->id }}" {{ old('ciudad_id',$proveedor->ciudad_id) == $ciu->id ? 'selected' : '' }}>{{ $ciu->nombre_completo }}</option>
    @endforeach
    </select></div>
<div class="col-md-6"><label class="form-label fw-semibold">País</label>
    <input type="text" name="pais" class="form-control" value="{{ old('pais', $proveedor->pais) }}"></div>
<div class="col-12"><div class="form-check">
    <input type="checkbox" name="activo" value="1" class="form-check-input" {{ $proveedor->activo ? 'checked' : '' }}>
    <label class="form-check-label">Proveedor activo</label>
</div></div>
<div class="col-md-6"><div class="form-check"><input type="checkbox" name="expuesto_publicamente" value="1" class="form-check-input" {{ old('expuesto_publicamente', $proveedor->expuesto_publicamente) ? 'checked' : '' }}><label class="form-check-label">Persona Expuesta Públicamente (PEP)</label></div></div>
<div class="col-md-6"><div class="form-check"><input type="checkbox" name="funcionario" value="1" class="form-check-input" {{ old('funcionario', $proveedor->funcionario) ? 'checked' : '' }}><label class="form-check-label">Es funcionario público</label></div></div>
<div class="col-12"><label class="form-label fw-semibold">Etiquetas</label><input type="text" name="etiquetas" class="form-control" placeholder="Confiable, Entrega rápida" value="{{ old('etiquetas', $etiquetasTexto) }}"><small class="text-muted">Separadas por coma</small></div>
<x-campos-personalizados :campos="$campos" :valores="$valores" />
</div>
<div class="d-flex gap-2 mt-4">
    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Actualizar</button>
    <a href="{{ route('proveedores.show',$proveedor) }}" class="btn btn-outline-secondary">Cancelar</a>
</div>
</form></div></div>
</div></div>
@endsection
