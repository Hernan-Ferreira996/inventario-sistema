@extends('layouts.app')
@section('titulo','Libro Diario')
@section('contenido')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Libro Diario</h5>
    @can('contabilidad.crear')
    <a href="{{ route('contabilidad.asientos.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Nuevo Asiento Manual</a>
    @endcan
</div>
<div class="card mb-3"><div class="card-body py-2">
<div class="btn-group btn-group-sm">
    <a href="{{ route('contabilidad.reportes.balance-comprobacion') }}" class="btn btn-outline-primary">Balance de Comprobación</a>
    <a href="{{ route('contabilidad.reportes.estado-resultados') }}" class="btn btn-outline-primary">Estado de Resultados</a>
    <a href="{{ route('contabilidad.reportes.balance-general') }}" class="btn btn-outline-primary">Balance General</a>
    <a href="{{ route('contabilidad.cuentas.index') }}" class="btn btn-outline-secondary">Plan de Cuentas</a>
</div>
</div></div>
<div class="card">
<div class="table-responsive">
<table class="table table-hover mb-0 align-middle">
<thead class="table-light"><tr><th>N°</th><th>Fecha</th><th>Concepto</th><th>Origen</th><th class="text-center">Movs.</th><th></th></tr></thead>
<tbody>
@forelse($asientos as $a)
<tr>
    <td class="fw-semibold">{{ $a->numero }}</td>
    <td>{{ $a->fecha->format('d/m/Y') }}</td>
    <td>{{ $a->concepto }}</td>
    <td><span class="badge bg-light text-dark border">{{ ucfirst(str_replace('_',' ',$a->origen)) }}</span></td>
    <td class="text-center">{{ $a->movimientos_count }}</td>
    <td><a href="{{ route('contabilidad.asientos.show',$a) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a></td>
</tr>
@empty
<tr><td colspan="6" class="text-center py-4 text-muted">Sin asientos registrados todavía.</td></tr>
@endforelse
</tbody>
</table>
</div>
</div>
{{ $asientos->links() }}
@endsection
