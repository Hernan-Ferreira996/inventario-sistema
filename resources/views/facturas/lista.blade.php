@extends('layouts.app')
@section('titulo','Facturas')
@section('contenido')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">
        Facturas
        @if($clienteFiltrado)
        <span class="badge bg-primary fs-6 ms-2">{{ $clienteFiltrado->nombre }}
            <a href="{{ route('facturas.index') }}" class="text-white ms-1" title="Quitar filtro"><i class="bi bi-x-circle"></i></a>
        </span>
        @endif
    </h5>
</div>
<div class="card mb-3"><div class="card-body py-2">
<form method="GET" class="row g-2">
    @if($clienteFiltrado)<input type="hidden" name="cliente_id" value="{{ $clienteFiltrado->id }}">@endif
    <div class="col-md-8"><input type="text" name="q" class="form-control" placeholder="Buscar por N° factura o cliente..." value="{{ request('q') }}"></div>
    <div class="col-md-2"><button class="btn btn-outline-primary w-100"><i class="bi bi-search me-1"></i>Buscar</button></div>
    <div class="col-md-2">@if(request()->hasAny(['q','cliente_id']))<a href="{{ route('facturas.index') }}" class="btn btn-outline-danger w-100">Limpiar</a>@endif</div>
</form>
</div></div>
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light"><tr>
                <th>N° Documento</th><th>Cliente</th><th>Fecha</th><th class="text-end">Total</th>
                <th class="text-end">Pagado</th><th class="text-center">Estado</th><th class="text-center">Modo</th><th>Acciones</th>
            </tr></thead>
            <tbody>
            @forelse($facturas as $f)
            <tr>
                <td class="fw-semibold"><a href="{{ route('facturas.show',$f) }}" class="text-decoration-none">{{ $f->numero_documento }}</a></td>
                <td>{{ $f->pedido?->cliente?->nombre ?? '—' }}</td>
                <td>{{ $f->fecha_factura->format('d/m/Y') }}</td>
                <td class="text-end fw-semibold">{{ number_format($f->total,0,',','.') }}</td>
                <td class="text-end text-success">{{ number_format($f->monto_pagado,0,',','.') }}</td>
                <td class="text-center"><span class="badge badge-estado-{{ $f->estado }}">{{ ucfirst($f->estado) }}</span></td>
                <td class="text-center">
                    @if($f->modo === 'local')<span class="badge bg-warning text-dark">Demo</span>
                    @else<span class="badge bg-success">Electrónico</span>@endif
                </td>
                <td><div class="d-flex gap-1">
                    <a href="{{ route('facturas.show',$f) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                    <a href="{{ route('facturas.pdf',$f) }}" target="_blank" class="btn btn-sm btn-outline-secondary"><i class="bi bi-file-pdf"></i></a>
                </div></td>
            </tr>
            @empty
            <tr><td colspan="8" class="text-center py-5 text-muted">
                <i class="bi bi-receipt d-block mb-2" style="font-size:2rem"></i>
                Sin facturas generadas. Se generan desde el detalle de un Pedido de Venta.
            </td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($facturas->hasPages())<div class="card-footer py-2">{{ $facturas->links() }}</div>@endif
</div>
@endsection
