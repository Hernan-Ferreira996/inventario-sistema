@extends('layouts.app')
@section('titulo','Usuarios')
@section('contenido')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Usuarios del Sistema</h5>
    <div class="d-flex gap-2">
        <a href="{{ route('grupos.index') }}" class="btn btn-outline-secondary"><i class="bi bi-shield-gear me-1"></i>Gestionar Grupos</a>
        <a href="{{ route('usuarios.create') }}" class="btn btn-primary"><i class="bi bi-person-plus me-1"></i>Nuevo Usuario</a>
    </div>
</div>
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light"><tr>
                <th>Nombre</th><th>Email</th><th>Grupos de Acceso</th><th>Registrado</th><th>Acciones</th>
            </tr></thead>
            <tbody>
            @forelse($usuarios as $u)
            <tr>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <span class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center flex-shrink-0"
                            style="width:34px;height:34px;font-size:.9rem;font-weight:700">{{ strtoupper(substr($u->name,0,1)) }}</span>
                        <span class="fw-semibold">{{ $u->name }}</span>
                        @if($u->id === auth()->id())<span class="badge bg-primary ms-1">Tú</span>@endif
                    </div>
                </td>
                <td class="text-muted">{{ $u->email }}</td>
                <td>
                    @forelse($u->roles as $rol)
                    <span class="badge bg-secondary me-1">{{ ucfirst($rol->name) }}</span>
                    @empty
                    <span class="text-muted small">Sin grupo asignado</span>
                    @endforelse
                </td>
                <td><small>{{ $u->created_at->format('d/m/Y') }}</small></td>
                <td><div class="d-flex gap-1">
                    <a href="{{ route('usuarios.show',$u) }}" class="btn btn-sm btn-outline-info" title="Ver detalle"><i class="bi bi-eye"></i></a>
                    <a href="{{ route('usuarios.edit',$u) }}" class="btn btn-sm btn-outline-warning" title="Editar"><i class="bi bi-pencil"></i></a>
                    @if($u->id !== auth()->id())
                    <form method="POST" action="{{ route('usuarios.destroy',$u) }}" onsubmit="return confirm('Eliminar usuario?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                    </form>
                    @endif
                </div></td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center py-5 text-muted">Sin usuarios</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($usuarios->hasPages())<div class="card-footer py-2">{{ $usuarios->links() }}</div>@endif
</div>
@endsection
