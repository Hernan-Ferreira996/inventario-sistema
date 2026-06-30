@extends('layouts.app')
@section('titulo','Reporte de Ventas')
@section('contenido')
<div class="card mb-3"><div class="card-body py-2">
<form method="GET" class="row g-2 align-items-end">
<div class="col-auto"><label class="form-label mb-1 small">Desde</label><input type="date" name="desde" class="form-control form-control-sm" value="{{ $desde }}"></div>
<div class="col-auto"><label class="form-label mb-1 small">Hasta</label><input type="date" name="hasta" class="form-control form-control-sm" value="{{ $hasta }}"></div>
<div class="col-auto"><button class="btn btn-primary btn-sm">Filtrar</button></div>
<div class="col-auto ms-auto d-flex gap-2">
    @can('reportes.exportar')
    <a href="{{ route('reportes.ventas.excel', request()->query()) }}" class="btn btn-outline-success btn-sm"><i class="bi bi-file-earmark-excel me-1"></i>Excel</a>
    @endcan
    <a href="{{ route('reportes.ventas.pdf', request()->query()) }}" target="_blank" class="btn btn-outline-secondary btn-sm"><i class="bi bi-file-pdf me-1"></i>PDF</a>
</div>
</form></div></div>
<div class="card"><div class="card-header d-flex justify-content-between">
<span>Ventas {{ $desde }} al {{ $hasta }}</span>
<span class="fw-bold text-success">Total: {{ number_format($total,2) }}</span>
</div>
<div class="table-responsive"><table class="table table-hover mb-0">
<thead><tr><th>Referencia</th><th>Cliente</th><th>Fecha</th><th class="text-end">Total</th><th class="text-end">Pagado</th></tr></thead>
<tbody>
@forelse($pedidos as $p)
<tr>
<td class="fw-semibold">{{ $p->numero_referencia }}</td>
<td>{{ $p->cliente?->nombre ?? '—' }}</td>
<td>{{ $p->fecha_pedido->format('d/m/Y') }}</td>
<td class="text-end">{{ number_format($p->total,2) }}</td>
<td class="text-end text-success">{{ number_format($p->monto_pagado,2) }}</td>
</tr>
@empty
<tr><td colspan="5" class="text-center py-4 text-muted">Sin ventas en el periodo</td></tr>
@endforelse
</tbody>
</table></div></div>
@endsection