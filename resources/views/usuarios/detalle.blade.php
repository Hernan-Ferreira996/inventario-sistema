@extends('layouts.app')
@section('titulo', $usuario->name)
@section('contenido')
<div class="row g-3">
<div class="col-md-4">
    <div class="card mb-3">
        <div class="card-body text-center p-4">
            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-3"
                style="width:64px;height:64px;font-size:1.8rem;font-weight:700">{{ strtoupper(substr($usuario->name,0,1)) }}</div>
            <h5 class="fw-bold mb-0">{{ $usuario->name }}</h5>
            <div class="text-muted small">{{ $usuario->email }}</div>
            <div class="mt-2">
            @foreach($usuario->roles as $rol)
            <span class="badge bg-primary me-1">{{ ucfirst($rol->name) }}</span>
            @endforeach
            </div>
        </div>
        <div class="card-footer d-grid gap-2">
            <a href="{{ route('usuarios.edit',$usuario) }}" class="btn btn-outline-warning"><i class="bi bi-pencil me-1"></i>Editar</a>
            <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">Volver</a>
        </div>
    </div>
</div>
<div class="col-md-8">
    <div class="card">
        <div class="card-header fw-semibold">Permisos efectivos (heredados de sus grupos)</div>
        <div class="card-body">
        @forelse($usuario->roles as $rol)
        <h6 class="text-primary fw-semibold mt-2">Grupo: {{ ucfirst($rol->name) }}</h6>
        <div class="mb-3">
            @forelse($rol->permissions as $perm)
            <span class="badge bg-light text-secondary border me-1 mb-1">{{ $perm->name }}</span>
            @empty
            <span class="text-muted small">Sin permisos asignados</span>
            @endforelse
        </div>
        @empty
        <div class="text-muted text-center py-3">Este usuario no tiene grupos asignados — sin acceso al sistema.</div>
        @endforelse
        </div>
    </div>
</div>
</div>
@endsection
