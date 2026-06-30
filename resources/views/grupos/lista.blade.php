@extends('layouts.app')
@section('titulo','Grupos de Acceso')
@section('contenido')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Grupos de Acceso</h5>
    <a href="{{ route('grupos.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Nuevo Grupo</a>
</div>

<div class="row g-3">
@forelse($grupos as $g)
<div class="col-md-4">
    <div class="card h-100">
        <div class="card-body">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center flex-shrink-0" style="width:44px;height:44px;font-size:1.2rem">
                    {{ strtoupper(substr($g->name,0,1)) }}
                </div>
                <div>
                    <h6 class="fw-bold mb-0">{{ ucfirst($g->name) }}</h6>
                    <small class="text-muted">{{ $g->users_count }} usuario(s)</small>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('grupos.show',$g) }}" class="btn btn-sm btn-primary flex-grow-1"><i class="bi bi-shield-check me-1"></i>Gestionar Permisos</a>
                <a href="{{ route('grupos.edit',$g) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                @if($g->name !== 'admin')
                <form method="POST" action="{{ route('grupos.destroy',$g) }}" onsubmit="return confirm('¿Eliminar grupo?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@empty
<div class="col-12"><div class="alert alert-info">Sin grupos. <a href="{{ route('grupos.create') }}">Crear el primero</a></div></div>
@endforelse
</div>
@endsection
