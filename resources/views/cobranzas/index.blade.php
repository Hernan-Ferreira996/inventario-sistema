@extends('layouts.app')
@section('titulo','Cobranzas')
@section('contenido')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">
        Cobranzas
        @if($clienteFiltrado)
        <span class="badge bg-primary fs-6 ms-2">{{ $clienteFiltrado->nombre }}
            <a href="{{ route('cobranzas.index') }}" class="text-white ms-1" title="Quitar filtro"><i class="bi bi-x-circle"></i></a>
        </span>
        @endif
    </h5>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-4">
        <div class="card"><div class="card-body">
            <small class="text-muted d-block">Saldo total pendiente</small>
            <span class="fs-4 fw-bold">{{ number_format($totales['saldo_total'],0,',','.') }}</span>
        </div></div>
    </div>
    <div class="col-md-4">
        <div class="card border-danger"><div class="card-body">
            <small class="text-muted d-block">Saldo vencido</small>
            <span class="fs-4 fw-bold text-danger">{{ number_format($totales['saldo_vencido'],0,',','.') }}</span>
        </div></div>
    </div>
    <div class="col-md-4">
        <div class="card"><div class="card-body">
            <small class="text-muted d-block">Facturas vencidas</small>
            <span class="fs-4 fw-bold">{{ $totales['cantidad_vencidas'] }}</span>
        </div></div>
    </div>
</div>

<div class="card mb-3"><div class="card-body py-2">
<form method="GET" class="row g-2 align-items-center">
    <div class="col-md-5">
        <select name="cliente_id" class="form-select" onchange="this.form.submit()">
            <option value="">-- Todos los clientes --</option>
            @foreach($clientes as $c)
            <option value="{{ $c->id }}" {{ request('cliente_id') == $c->id ? 'selected' : '' }}>{{ $c->nombre }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4 form-check d-flex align-items-center gap-2">
        <input type="checkbox" name="solo_vencidas" value="1" class="form-check-input" id="chkVencidas" {{ request('solo_vencidas') ? 'checked' : '' }} onchange="this.form.submit()">
        <label class="form-check-label" for="chkVencidas">Solo vencidas</label>
    </div>
    <div class="col-md-3">@if(request()->hasAny(['cliente_id','solo_vencidas']))<a href="{{ route('cobranzas.index') }}" class="btn btn-outline-danger w-100">Limpiar filtros</a>@endif</div>
</form>
</div></div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light"><tr>
                <th>N° Documento</th><th>Cliente</th><th>Fecha</th><th>Vencimiento</th>
                <th class="text-end">Total</th><th class="text-end">Pagado</th><th class="text-end">Saldo</th><th>Estado</th><th>Acciones</th>
            </tr></thead>
            <tbody>
            @forelse($facturas as $f)
            <tr class="{{ $f->estaVencida() ? 'table-danger' : '' }}">
                <td class="fw-semibold"><a href="{{ route('facturas.show',$f) }}" class="text-decoration-none">{{ $f->numero_documento }}</a></td>
                <td>{{ $f->pedido?->cliente?->nombre ?? '—' }}</td>
                <td>{{ $f->fecha_factura->format('d/m/Y') }}</td>
                <td>
                    @if($f->fecha_vencimiento)
                        {{ $f->fecha_vencimiento->format('d/m/Y') }}
                        @if($f->estaVencida())<br><small class="text-danger fw-semibold">{{ $f->diasVencida() }} días vencida</small>@endif
                    @else
                        <span class="text-muted">Contado</span>
                    @endif
                </td>
                <td class="text-end">{{ number_format($f->total,0,',','.') }}</td>
                <td class="text-end text-success">{{ number_format($f->monto_pagado,0,',','.') }}</td>
                <td class="text-end fw-semibold {{ $f->saldo_pendiente > 0 ? 'text-danger' : '' }}">{{ number_format($f->saldo_pendiente,0,',','.') }}</td>
                <td><x-badge-estado grupo="facturas.estado" :valor="$f->estado" /></td>
                <td>
                    <a href="{{ route('facturas.show',$f) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                </td>
            </tr>
            @empty
            <tr><td colspan="9" class="text-center py-5 text-muted">
                <i class="bi bi-check-circle d-block mb-2" style="font-size:2rem"></i>
                Sin facturas con saldo pendiente.
            </td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($facturas->hasPages())<div class="card-footer py-2">{{ $facturas->links() }}</div>@endif
</div>
@endsection
