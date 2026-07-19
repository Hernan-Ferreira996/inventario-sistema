@extends('layouts.app')
@section('titulo','Cajas')
@section('contenido')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Cajas</h5>
    <a href="{{ route('cajas.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Nueva Caja</a>
</div>
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light"><tr>
                <th>Nombre</th><th class="text-center">Pagos</th><th class="text-center">Estado</th><th>Acciones</th>
            </tr></thead>
            <tbody>
            @forelse($cajas as $c)
            <tr>
                <td class="fw-semibold">{{ $c->nombre }}</td>
                <td class="text-center"><span class="badge bg-secondary">{{ $c->pagos_count }}</span></td>
                <td class="text-center">
                    @if($c->activo)<span class="badge bg-success">Activa</span>@else<span class="badge bg-secondary">Inactiva</span>@endif
                </td>
                <td><div class="d-flex gap-1">
                    <a href="{{ route('cajas.edit',$c) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                    <form method="POST" action="{{ route('cajas.destroy',$c) }}" class="d-inline" onsubmit="return confirm('¿Eliminar esta caja?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                    </form>
                </div></td>
            </tr>
            @empty
            <tr><td colspan="4" class="text-center py-5 text-muted">
                <i class="bi bi-cash-stack d-block mb-2" style="font-size:2rem"></i>
                Sin cajas. <a href="{{ route('cajas.create') }}">Crear la primera</a>
            </td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($cajas->hasPages())<div class="card-footer py-2">{{ $cajas->links() }}</div>@endif
</div>
@endsection
