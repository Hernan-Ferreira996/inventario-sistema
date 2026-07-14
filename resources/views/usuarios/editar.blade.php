@extends('layouts.app')
@section('titulo','Editar Usuario')
@section('contenido')
<div class="row justify-content-center"><div class="col-lg-7">
<div class="card"><div class="card-header fw-semibold">Editar: {{ $usuario->name }}</div>
<div class="card-body">
<form method="POST" action="{{ route('usuarios.update',$usuario) }}">@csrf @method('PATCH')
<div class="row g-3">
<div class="col-md-6">
    <label class="form-label fw-semibold">Nombre completo *</label>
    <input type="text" name="name" class="form-control" value="{{ old('name',$usuario->name) }}" required>
</div>
<div class="col-md-6">
    <label class="form-label fw-semibold">Email *</label>
    <input type="email" name="email" class="form-control" value="{{ old('email',$usuario->email) }}" required>
</div>
<div class="col-12"><hr class="my-2">
<p class="text-muted small mb-2"><i class="bi bi-lock me-1"></i>Dejar en blanco para no cambiar la contraseña</p></div>
<div class="col-md-6">
    <label class="form-label fw-semibold">Nueva Contraseña</label>
    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="col-md-6">
    <label class="form-label fw-semibold">Confirmar Contraseña</label>
    <input type="password" name="password_confirmation" class="form-control">
</div>
<div class="col-12"><hr class="my-2">
    <label class="form-label fw-semibold">Grupos de Acceso</label>
    <small class="text-muted d-block mb-2">Grupos asignados actualmente a este usuario</small>
    <div class="row g-2">
    @foreach($grupos as $g)
    <div class="col-md-4">
        <div class="card border-2 h-100">
            <div class="card-body py-2 px-3">
                <div class="form-check">
                    <input type="checkbox" name="grupos[]" value="{{ $g->id }}" class="form-check-input" id="g{{ $g->id }}"
                        {{ $usuario->hasRole($g->name) ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold" for="g{{ $g->id }}">{{ ucfirst($g->name) }}</label>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    </div>
</div>
@if($sucursales->count() > 1)
<div class="col-12"><hr class="my-2">
    <label class="form-label fw-semibold">Sucursales Permitidas</label>
    <small class="text-muted d-block mb-2">Sin marcar ninguna, el usuario ve todas las sucursales de su empresa. Marcando una o más, queda restringido exactamente a esas.</small>
    <div class="row g-2">
    @foreach($sucursales as $s)
    <div class="col-md-4">
        <div class="card border-2 h-100">
            <div class="card-body py-2 px-3">
                <div class="form-check">
                    <input type="checkbox" name="sucursales[]" value="{{ $s->id }}" class="form-check-input" id="suc{{ $s->id }}"
                        {{ $usuario->sucursales->contains($s->id) ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold" for="suc{{ $s->id }}">{{ $s->nombre }}</label>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    </div>
</div>
@endif
@if($ubicaciones->flatten()->count() > 1)
<div class="col-12"><hr class="my-2">
    <label class="form-label fw-semibold">Depósitos Permitidos</label>
    <small class="text-muted d-block mb-2">Sin marcar ninguno, el usuario ve todos los depósitos de sus sucursales permitidas. Marcando uno o más, queda restringido exactamente a esos.</small>
    @foreach($ubicaciones as $nombreSucursal => $depositos)
    <div class="mb-2">
        <small class="text-muted fw-semibold d-block mb-1">{{ $nombreSucursal }}</small>
        <div class="row g-2">
        @foreach($depositos as $u)
        <div class="col-md-4">
            <div class="card border-2 h-100">
                <div class="card-body py-2 px-3">
                    <div class="form-check">
                        <input type="checkbox" name="ubicaciones[]" value="{{ $u->id }}" class="form-check-input" id="ubi{{ $u->id }}"
                            {{ $usuario->ubicaciones->contains($u->id) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="ubi{{ $u->id }}">{{ $u->nombre }}</label>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        </div>
    </div>
    @endforeach
</div>
@endif
</div>
<div class="d-flex gap-2 mt-4 pt-3 border-top">
    <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i>Actualizar</button>
    <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">Cancelar</a>
</div>
</form>
</div></div>
</div></div>
@endsection
