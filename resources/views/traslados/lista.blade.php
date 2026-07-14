@extends('layouts.app')
@section('titulo','Traslados de Stock')
@section('contenido')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Traslados entre Almacenes</h5>
    @can('productos.editar')
    @if(!Auth::user()?->esSuperAdmin())
    <a href="{{ route('traslados.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Nuevo Traslado</a>
    @endif
    @endcan
</div>
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light"><tr><th>Fecha</th><th>Origen</th><th>Destino</th><th>Referencia</th><th>Usuario</th><th></th></tr></thead>
            <tbody>
            @forelse($traslados as $t)
            <tr>
                <td>{{ $t->fecha_traslado->format('d/m/Y') }}</td>
                <td><span class="badge bg-secondary">{{ $t->ubicacionOrigen->nombre ?? '—' }}</span></td>
                <td><span class="badge bg-primary">{{ $t->ubicacionDestino->nombre ?? '—' }}</span></td>
                <td>{{ $t->referencia ?: '—' }}</td>
                <td>{{ $t->usuario->name ?? '—' }}</td>
                <td><a href="{{ route('traslados.show',$t) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a></td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center py-5 text-muted">
                <i class="bi bi-arrow-left-right d-block mb-2" style="font-size:2rem"></i>
                Sin traslados registrados. @if(!Auth::user()?->esSuperAdmin())<a href="{{ route('traslados.create') }}">Crear el primero</a>@endif
            </td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($traslados->hasPages())<div class="card-footer py-2">{{ $traslados->links() }}</div>@endif
</div>
@endsection
