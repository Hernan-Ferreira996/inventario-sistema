@extends('layouts.app')
@section('titulo','Reporte de Compras')
@section('contenido')

<div class="card mb-3"><div class="card-body py-2">
<form method="GET" class="row g-2 align-items-end">
    <div class="col-md-3"><label class="form-label small fw-semibold mb-1">Desde</label><input type="date" name="desde" class="form-control" value="{{ $desde }}"></div>
    <div class="col-md-3"><label class="form-label small fw-semibold mb-1">Hasta</label><input type="date" name="hasta" class="form-control" value="{{ $hasta }}"></div>
    <div class="col-md-2"><button class="btn btn-outline-primary w-100"><i class="bi bi-search me-1"></i>Filtrar</button></div>
</form>
</div></div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-semibold">Compras del {{ \Carbon\Carbon::parse($desde)->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($hasta)->format('d/m/Y') }}</span>
        <span class="badge bg-primary fs-6">Total: {{ number_format($total,0,',','.') }}</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light"><tr><th>N° Referencia</th><th>Proveedor</th><th>Fecha</th><th class="text-end">Total</th><th>Estado</th></tr></thead>
            <tbody>
            @forelse($pedidos as $p)
            <tr>
                <td class="fw-semibold"><a href="{{ route('compras.show',$p) }}" class="text-decoration-none">{{ $p->numero_referencia }}</a></td>
                <td>{{ $p->proveedor->nombre ?? '—' }}</td>
                <td>{{ $p->fecha_pedido->format('d/m/Y') }}</td>
                <td class="text-end">{{ number_format($p->total,0,',','.') }}</td>
                <td><x-badge-estado grupo="pedidos_compra.estado" :valor="$p->estado" /></td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center py-5 text-muted">Sin compras en este período</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
