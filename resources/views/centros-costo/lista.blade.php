@extends('layouts.app')
@section('titulo','Centros de Costo')
@section('contenido')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Centros de Costo</h5>
    @can('centros_costo.crear')
    <a href="{{ route('centros-costo.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Nuevo Centro de Costo</a>
    @endcan
</div>
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light"><tr>
                <th>Código</th><th>Nombre</th><th>Descripción</th><th class="text-center">Compras</th><th class="text-center">Estado</th><th>Acciones</th>
            </tr></thead>
            <tbody>
            @forelse($centrosCosto as $c)
            <tr>
                <td class="fw-semibold"><code>{{ $c->codigo }}</code></td>
                <td>{{ $c->nombre }}</td>
                <td class="text-muted">{{ $c->descripcion ?? '—' }}</td>
                <td class="text-center"><span class="badge bg-secondary">{{ $c->pedidos_compra_count }}</span></td>
                <td class="text-center">
                    @if($c->activo)<span class="badge bg-success">Activo</span>@else<span class="badge bg-secondary">Inactivo</span>@endif
                </td>
                <td><div class="d-flex gap-1">
                    @can('centros_costo.editar')
                    <a href="{{ route('centros-costo.edit',$c) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                    @endcan
                    @can('centros_costo.eliminar')
                    <form method="POST" action="{{ route('centros-costo.destroy',$c) }}" class="d-inline" onsubmit="return confirm('¿Eliminar este centro de costo?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                    </form>
                    @endcan
                </div></td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center py-5 text-muted">
                <i class="bi bi-diagram-3 d-block mb-2" style="font-size:2rem"></i>
                Sin centros de costo. @can('centros_costo.crear')<a href="{{ route('centros-costo.create') }}">Crear el primero</a>@endcan
            </td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($centrosCosto->hasPages())<div class="card-footer py-2">{{ $centrosCosto->links() }}</div>@endif
</div>
@endsection
