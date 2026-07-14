@extends('layouts.app')
@section('titulo','Estado de Resultados')
@section('contenido')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Estado de Resultados</h5>
    <a href="{{ route('contabilidad.asientos.index') }}" class="btn btn-outline-secondary btn-sm">Volver al Libro Diario</a>
</div>
<div class="row justify-content-center"><div class="col-lg-7">
<div class="card mb-3">
<div class="card-header fw-semibold">Ingresos</div>
<div class="list-group list-group-flush">
@forelse($ingresos as $i)
<div class="list-group-item d-flex justify-content-between"><span>{{ $i['cuenta']->nombre }}</span><span>{{ number_format($i['saldo'],0,',','.') }}</span></div>
@empty
<div class="list-group-item text-muted small">Sin ingresos registrados.</div>
@endforelse
<div class="list-group-item d-flex justify-content-between fw-bold bg-light"><span>Total Ingresos</span><span>{{ number_format($totalIngresos,0,',','.') }}</span></div>
</div>
</div>

<div class="card mb-3">
<div class="card-header fw-semibold">Gastos</div>
<div class="list-group list-group-flush">
@forelse($gastos as $g)
<div class="list-group-item d-flex justify-content-between"><span>{{ $g['cuenta']->nombre }}</span><span>{{ number_format($g['saldo'],0,',','.') }}</span></div>
@empty
<div class="list-group-item text-muted small">Sin gastos registrados.</div>
@endforelse
<div class="list-group-item d-flex justify-content-between fw-bold bg-light"><span>Total Gastos</span><span>{{ number_format($totalGastos,0,',','.') }}</span></div>
</div>
</div>

<div class="card">
<div class="card-body d-flex justify-content-between align-items-center">
    <span class="fw-bold fs-5">Resultado del Ejercicio</span>
    <span class="fw-bold fs-5 {{ $resultado >= 0 ? 'text-success' : 'text-danger' }}">{{ number_format($resultado,0,',','.') }}</span>
</div>
</div>
</div></div>
@endsection
