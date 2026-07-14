@extends('layouts.app')
@section('titulo', 'Asiento ' . $asiento->numero)
@section('contenido')
<div class="card mb-3"><div class="card-body">
    <div class="row">
        <div class="col-md-3"><small class="text-muted d-block">N° Asiento</small><strong>{{ $asiento->numero }}</strong></div>
        <div class="col-md-3"><small class="text-muted d-block">Fecha</small>{{ $asiento->fecha->format('d/m/Y') }}</div>
        <div class="col-md-3"><small class="text-muted d-block">Origen</small><span class="badge bg-light text-dark border">{{ ucfirst(str_replace('_',' ',$asiento->origen)) }}</span></div>
        <div class="col-md-3"><small class="text-muted d-block">Usuario</small>{{ $asiento->usuario->name ?? '—' }}</div>
        <div class="col-12 mt-2"><small class="text-muted d-block">Concepto</small>{{ $asiento->concepto }}</div>
    </div>
</div></div>
<div class="card">
<div class="table-responsive">
<table class="table mb-0 align-middle">
<thead class="table-light"><tr><th>Cuenta</th><th>Descripción</th><th class="text-end">Debe</th><th class="text-end">Haber</th></tr></thead>
<tbody>
@foreach($asiento->movimientos as $m)
<tr>
    <td><code>{{ $m->cuenta->codigo }}</code> {{ $m->cuenta->nombre }}</td>
    <td class="text-muted small">{{ $m->descripcion }}</td>
    <td class="text-end">{{ $m->debe > 0 ? number_format($m->debe,0,',','.') : '' }}</td>
    <td class="text-end">{{ $m->haber > 0 ? number_format($m->haber,0,',','.') : '' }}</td>
</tr>
@endforeach
</tbody>
<tfoot><tr class="fw-bold table-light">
    <td colspan="2" class="text-end">Totales:</td>
    <td class="text-end">{{ number_format($asiento->total_debe,0,',','.') }}</td>
    <td class="text-end">{{ number_format($asiento->total_haber,0,',','.') }}</td>
</tr></tfoot>
</table>
</div>
</div>
<div class="mt-3"><a href="{{ route('contabilidad.asientos.index') }}" class="btn btn-outline-secondary">Volver al Libro Diario</a></div>
@endsection
