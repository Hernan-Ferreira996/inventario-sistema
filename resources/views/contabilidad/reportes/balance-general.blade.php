@extends('layouts.app')
@section('titulo','Balance General')
@section('contenido')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Balance General</h5>
    <a href="{{ route('contabilidad.asientos.index') }}" class="btn btn-outline-secondary btn-sm">Volver al Libro Diario</a>
</div>
<div class="row g-3">
<div class="col-md-6">
<div class="card mb-3">
<div class="card-header fw-semibold">Activo</div>
<div class="list-group list-group-flush">
@forelse($activos as $a)
<div class="list-group-item d-flex justify-content-between"><span>{{ $a['cuenta']->nombre }}</span><span>{{ number_format($a['saldo'],0,',','.') }}</span></div>
@empty
<div class="list-group-item text-muted small">Sin cuentas de activo con saldo.</div>
@endforelse
<div class="list-group-item d-flex justify-content-between fw-bold bg-light"><span>Total Activo</span><span>{{ number_format($totalActivo,0,',','.') }}</span></div>
</div>
</div>
</div>
<div class="col-md-6">
<div class="card mb-3">
<div class="card-header fw-semibold">Pasivo</div>
<div class="list-group list-group-flush">
@forelse($pasivos as $p)
<div class="list-group-item d-flex justify-content-between"><span>{{ $p['cuenta']->nombre }}</span><span>{{ number_format($p['saldo'],0,',','.') }}</span></div>
@empty
<div class="list-group-item text-muted small">Sin cuentas de pasivo con saldo.</div>
@endforelse
<div class="list-group-item d-flex justify-content-between fw-bold bg-light"><span>Total Pasivo</span><span>{{ number_format($totalPasivo,0,',','.') }}</span></div>
</div>
</div>
<div class="card mb-3">
<div class="card-header fw-semibold">Patrimonio</div>
<div class="list-group list-group-flush">
@forelse($patrimonios as $p)
<div class="list-group-item d-flex justify-content-between"><span>{{ $p['cuenta']->nombre }}</span><span>{{ number_format($p['saldo'],0,',','.') }}</span></div>
@empty
<div class="list-group-item text-muted small">Sin cuentas de patrimonio con saldo.</div>
@endforelse
<div class="list-group-item d-flex justify-content-between"><span>Resultado del Ejercicio</span><span>{{ number_format($resultadoEjercicio,0,',','.') }}</span></div>
<div class="list-group-item d-flex justify-content-between fw-bold bg-light"><span>Total Patrimonio</span><span>{{ number_format($totalPatrimonio,0,',','.') }}</span></div>
</div>
</div>
</div>
</div>
<div class="card">
<div class="card-body d-flex justify-content-between align-items-center">
    <span class="fw-bold">Activo = Pasivo + Patrimonio</span>
    @php $cuadra = round($totalActivo,2) === round($totalPasivo + $totalPatrimonio,2); @endphp
    <span class="badge {{ $cuadra ? 'bg-success' : 'bg-danger' }} fs-6">
        {{ number_format($totalActivo,0,',','.') }} {{ $cuadra ? '=' : '≠' }} {{ number_format($totalPasivo + $totalPatrimonio,0,',','.') }}
    </span>
</div>
</div>
@endsection
