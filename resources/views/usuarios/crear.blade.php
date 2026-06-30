@extends('layouts.app')
@section('titulo','Nuevo Usuario')
@section('contenido')
<div class="row justify-content-center"><div class="col-lg-7">
<div class="card"><div class="card-header fw-semibold"><i class="bi bi-person-plus me-2 text-primary"></i>Nuevo Usuario</div>
<div class="card-body">
<form method="POST" action="{{ route('usuarios.store') }}">@csrf
<div class="row g-3">
<div class="col-md-6">
    <label class="form-label fw-semibold">Nombre completo *</label>
    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required autofocus>
    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="col-md-6">
    <label class="form-label fw-semibold">Email *</label>
    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="col-md-6">
    <label class="form-label fw-semibold">Contraseña *</label>
    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="col-md-6">
    <label class="form-label fw-semibold">Confirmar Contraseña *</label>
    <input type="password" name="password_confirmation" class="form-control" required>
</div>
@if(Auth::user()?->esSuperAdmin())
<div class="col-12">
    <label class="form-label fw-semibold">Empresa *</label>
    <select name="empresa_id" class="form-select" required>
        <option value="">-- Asignar a empresa --</option>
        @foreach(\App\Models\Empresa::where('activo',true)->orderBy('nombre')->get() as $emp)
        <option value="{{ $emp->id }}" {{ old('empresa_id') == $emp->id ? 'selected' : '' }}>{{ $emp->nombre_fantasia ?: $emp->nombre }}</option>
        @endforeach
    </select>
    <small class="text-muted">El usuario solo verá datos de la empresa seleccionada.</small>
</div>
@endif
<div class="col-md-0" style="display:none">
</div>
<div class="col-12">
    <label class="form-label fw-semibold">Grupos de Acceso</label>
    <small class="text-muted d-block mb-2">El usuario heredará todos los permisos de los grupos que se le asignen</small>
    <div class="row g-2">
    @foreach($grupos as $g)
    <div class="col-md-4">
        <div class="card border-2 {{ in_array(old('grupos', []), [$g->id]) ? 'border-primary' : '' }} h-100">
            <div class="card-body py-2 px-3">
                <div class="form-check">
                    <input type="checkbox" name="grupos[]" value="{{ $g->id }}" class="form-check-input" id="g{{ $g->id }}"
                        {{ in_array($g->id, old('grupos', [])) ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold" for="g{{ $g->id }}">{{ ucfirst($g->name) }}</label>
                </div>
                <div class="mt-1">
                    @foreach($g->permissions->take(4) as $p)
                    <span class="badge bg-light text-secondary me-1" style="font-size:.65rem">{{ $p->name }}</span>
                    @endforeach
                    @if($g->permissions->count() > 4)
                    <span class="text-muted" style="font-size:.7rem">+{{ $g->permissions->count() - 4 }} más</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endforeach
    </div>
    <div class="mt-2">
        <a href="{{ route('grupos.index') }}" class="text-primary small"><i class="bi bi-shield-gear me-1"></i>Ver y gestionar grupos de acceso</a>
    </div>
</div>
</div>
<div class="d-flex gap-2 mt-4 pt-3 border-top">
    <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i>Crear Usuario</button>
    <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">Cancelar</a>
</div>
</form>
</div></div>
</div></div>
@endsection
