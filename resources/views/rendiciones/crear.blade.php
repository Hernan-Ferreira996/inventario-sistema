@extends('layouts.app')
@section('titulo','Nueva Rendición')
@section('contenido')
<div class="card mb-3">
    <div class="card-header fw-semibold">Seleccionar Caja y Cobrador</div>
    <div class="card-body">
        <form method="GET" action="{{ route('rendiciones.create') }}" class="row g-3 align-items-end">
            <div class="col-md-5">
                <label class="form-label fw-semibold">Caja *</label>
                <select name="caja_id" class="form-select" required>
                    <option value="">-- Seleccionar caja --</option>
                    @foreach($cajas as $c)
                    <option value="{{ $c->id }}" {{ request('caja_id') == $c->id ? 'selected' : '' }}>{{ $c->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-5">
                <label class="form-label fw-semibold">Cobrador *</label>
                <select name="cobrador_id" class="form-select" required>
                    <option value="">-- Seleccionar cobrador --</option>
                    @foreach($cobradores as $u)
                    <option value="{{ $u->id }}" {{ request('cobrador_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary w-100">Buscar</button>
            </div>
        </form>
    </div>
</div>

@if(request()->filled('caja_id') && request()->filled('cobrador_id'))
<div class="card">
    <div class="card-header fw-semibold">Pagos Pendientes de Rendir</div>
    <div class="card-body">
        @if($pagosPendientes->isEmpty())
        <p class="text-muted mb-0">No hay pagos pendientes de rendir para esta combinación de caja y cobrador.</p>
        @else
        <div class="table-responsive mb-3">
            <table class="table table-sm mb-0">
                <thead><tr><th>Recibo</th><th>Factura</th><th>Cliente</th><th>Fecha</th><th class="text-end">Monto</th></tr></thead>
                <tbody>
                @foreach($pagosPendientes as $p)
                <tr>
                    <td>{{ $p->numero_recibo }}</td>
                    <td>{{ $p->factura->numero_documento ?? '—' }}</td>
                    <td>{{ $p->factura->pedido->cliente->nombre ?? '—' }}</td>
                    <td>{{ $p->fecha_pago->format('d/m/Y') }}</td>
                    <td class="text-end">{{ number_format($p->monto,0,',','.') }}</td>
                </tr>
                @endforeach
                </tbody>
                <tfoot><tr class="fw-bold"><td colspan="4" class="text-end">Total a rendir:</td><td class="text-end">{{ number_format($pagosPendientes->sum('monto'),0,',','.') }}</td></tr></tfoot>
            </table>
        </div>

        <form method="POST" action="{{ route('rendiciones.store') }}">
            @csrf
            <input type="hidden" name="caja_id" value="{{ request('caja_id') }}">
            <input type="hidden" name="cobrador_id" value="{{ request('cobrador_id') }}">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Fecha de Rendición *</label>
                    <input type="date" name="fecha" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="col-md-8">
                    <label class="form-label fw-semibold">Observaciones</label>
                    <input type="text" name="observaciones" class="form-control">
                </div>
            </div>
            <div class="mt-3">
                <button type="submit" class="btn btn-primary" onclick="return confirm('¿Confirmás la rendición de {{ $pagosPendientes->count() }} pago(s) por un total de {{ number_format($pagosPendientes->sum('monto'),0,',','.') }}?')">
                    <i class="bi bi-check-circle me-1"></i>Confirmar Rendición
                </button>
            </div>
        </form>
        @endif
    </div>
</div>
@endif
@endsection
