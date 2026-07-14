@extends('layouts.app')
@section('titulo','Plan de Cuentas')
@section('contenido')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Plan de Cuentas</h5>
    @can('contabilidad.crear')
    <a href="{{ route('contabilidad.cuentas.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Nueva Cuenta</a>
    @endcan
</div>
<div class="card">
<div class="table-responsive">
<table class="table table-hover mb-0 align-middle">
<thead class="table-light"><tr><th>Código</th><th>Nombre</th><th>Tipo</th><th>Naturaleza</th><th>Imputable</th><th>Estado</th><th>Acciones</th></tr></thead>
<tbody>
@forelse($cuentas as $c)
<tr>
    <td><code>{{ $c->codigo }}</code></td>
    <td class="{{ $c->imputable ? '' : 'fw-bold' }}">{{ $c->nombre }}</td>
    <td><x-badge-estado grupo="cuentas_contables.tipo" :valor="$c->tipo" /></td>
    <td>{{ ucfirst($c->naturaleza) }}</td>
    <td>{{ $c->imputable ? 'Sí' : 'No (título)' }}</td>
    <td><span class="badge {{ $c->activo ? 'bg-success' : 'bg-secondary' }}">{{ $c->activo ? 'Activa' : 'Inactiva' }}</span></td>
    <td>
        @can('contabilidad.editar')
        <a href="{{ route('contabilidad.cuentas.edit',$c) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
        <form method="POST" action="{{ route('contabilidad.cuentas.destroy',$c) }}" class="d-inline" onsubmit="return confirm('¿Eliminar cuenta?')">@csrf @method('DELETE')
            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
        </form>
        @endcan
    </td>
</tr>
@empty
<tr><td colspan="7" class="text-center py-4 text-muted">Sin cuentas registradas.</td></tr>
@endforelse
</tbody>
</table>
</div>
</div>
@endsection
