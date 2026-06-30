@extends('layouts.app')
@section('titulo','Reporte de Stock')
@section('contenido')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0"><i class="bi bi-bar-chart me-2 text-primary"></i>Stock en Mano</h5>
    <div class="d-flex gap-2">
        @can('reportes.exportar')
        <a href="{{ route('reportes.stock.excel') }}" class="btn btn-outline-success btn-sm"><i class="bi bi-file-earmark-excel me-1"></i>Excel</a>
        @endcan
        <a href="{{ route('reportes.stock.pdf') }}" target="_blank" class="btn btn-outline-secondary btn-sm"><i class="bi bi-file-pdf me-1"></i>PDF</a>
    </div>
</div>
<div class="card">
<div class="card-header"><i class="bi bi-bar-chart me-2 text-primary"></i>Stock en Mano</div>
<div class="table-responsive">
<table class="table table-hover mb-0 align-middle">
<thead><tr><th>Codigo</th><th>Producto</th><th>Categoria</th><th class="text-end">Stock</th><th class="text-end">P. Compra</th><th class="text-end">Valor</th></tr></thead>
<tbody>
@php $totalValor = 0; @endphp
@forelse($productos as $p)
@php $stock = $p->movimientos_sum_cantidad ?? 0; $valor = $stock * $p->precio_compra; $totalValor += $valor; @endphp
<tr>
<td><code>{{ $p->codigo }}</code></td>
<td class="fw-semibold">{{ $p->nombre }}</td>
<td>{{ $p->categoria?->nombre ?? '—' }}</td>
<td class="text-end fw-semibold">{{ number_format($stock,2) }}</td>
<td class="text-end">{{ number_format($p->precio_compra,2) }}</td>
<td class="text-end">{{ number_format($valor,2) }}</td>
</tr>
@empty
<tr><td colspan="6" class="text-center py-4 text-muted">Sin productos</td></tr>
@endforelse
</tbody>
<tfoot><tr class="fw-bold"><td colspan="5" class="text-end">TOTAL:</td><td class="text-end">{{ number_format($totalValor,2) }}</td></tr></tfoot>
</table>
</div>
</div>
@endsection