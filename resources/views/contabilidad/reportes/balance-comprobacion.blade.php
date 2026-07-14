@extends('layouts.app')
@section('titulo','Balance de Comprobación')
@section('contenido')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Balance de Comprobación</h5>
    <a href="{{ route('contabilidad.asientos.index') }}" class="btn btn-outline-secondary btn-sm">Volver al Libro Diario</a>
</div>
<div class="card">
<div class="table-responsive">
<table class="table mb-0 align-middle">
<thead class="table-light"><tr><th>Código</th><th>Cuenta</th><th class="text-end">Debe</th><th class="text-end">Haber</th><th class="text-end">Saldo</th></tr></thead>
<tbody>
@forelse($filas as $f)
<tr>
    <td><code>{{ $f['cuenta']->codigo }}</code></td>
    <td>{{ $f['cuenta']->nombre }}</td>
    <td class="text-end">{{ number_format($f['debe'],0,',','.') }}</td>
    <td class="text-end">{{ number_format($f['haber'],0,',','.') }}</td>
    <td class="text-end fw-semibold">{{ number_format($f['saldo'],0,',','.') }}</td>
</tr>
@empty
<tr><td colspan="5" class="text-center py-4 text-muted">Sin movimientos contables todavía.</td></tr>
@endforelse
</tbody>
<tfoot><tr class="fw-bold table-light">
    <td colspan="2" class="text-end">Totales:</td>
    <td class="text-end">{{ number_format($filas->sum('debe'),0,',','.') }}</td>
    <td class="text-end">{{ number_format($filas->sum('haber'),0,',','.') }}</td>
    <td></td>
</tr></tfoot>
</table>
</div>
</div>
@endsection
