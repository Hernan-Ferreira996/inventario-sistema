@extends('layouts.app')
@section('titulo','Nuevo Cierre de Caja')
@section('contenido')
<div class="card mb-3">
    <div class="card-header fw-semibold">Seleccionar Caja</div>
    <div class="card-body">
        <form method="GET" action="{{ route('cierres-caja.create') }}" class="row g-3 align-items-end">
            <div class="col-md-8">
                <label class="form-label fw-semibold">Caja *</label>
                <select name="caja_id" class="form-select" required>
                    <option value="">-- Seleccionar caja --</option>
                    @foreach($cajas as $c)
                    <option value="{{ $c->id }}" {{ request('caja_id') == $c->id ? 'selected' : '' }}>{{ $c->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-outline-primary w-100">Calcular</button>
            </div>
        </form>
    </div>
</div>

@if($preview)
<div class="card">
    <div class="card-header fw-semibold">Resumen — {{ $preview['caja']->nombre }}</div>
    <div class="card-body">
        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <div class="p-3 bg-light rounded border text-center">
                    <div class="text-muted small">Saldo Inicial</div>
                    <div class="fs-5 fw-bold">{{ number_format($preview['saldo_inicial'],0,',','.') }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 bg-light rounded border text-center">
                    <div class="text-muted small">Total Cobrado</div>
                    <div class="fs-5 fw-bold text-success">{{ number_format($preview['total_cobrado'],0,',','.') }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 bg-light rounded border text-center">
                    <div class="text-muted small">Saldo Final</div>
                    <div class="fs-5 fw-bold">{{ number_format($preview['saldo_final'],0,',','.') }}</div>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('cierres-caja.store') }}">
            @csrf
            <input type="hidden" name="caja_id" value="{{ $preview['caja']->id }}">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Fecha de Cierre *</label>
                    <input type="date" name="fecha" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="col-md-8">
                    <label class="form-label fw-semibold">Observaciones</label>
                    <input type="text" name="observaciones" class="form-control">
                </div>
            </div>
            <div class="mt-3">
                <button type="submit" class="btn btn-danger" onclick="return confirm('¿Confirmás el cierre de esta caja?')">
                    <i class="bi bi-lock-fill me-1"></i>Confirmar Cierre
                </button>
            </div>
        </form>
    </div>
</div>
@endif
@endsection
