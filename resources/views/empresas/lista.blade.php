@extends('layouts.app')
@section('titulo','Empresas')
@section('contenido')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Empresas del Sistema</h5>
    <a href="{{ route('empresas.create') }}" class="btn btn-primary"><i class="bi bi-building-add me-1"></i>Nueva Empresa</a>
</div>
<div class="row g-3">
@forelse($empresas as $e)
<div class="col-md-4">
    <div class="card h-100 {{ !$e->activo ? 'border-secondary opacity-75' : '' }}">
        <div class="card-body">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center flex-shrink-0"
                    style="width:48px;height:48px;font-size:1.3rem;font-weight:700">{{ strtoupper(substr($e->nombre_fantasia ?: $e->nombre, 0, 1)) }}</div>
                <div>
                    <h6 class="fw-bold mb-0">{{ $e->nombre_fantasia ?: $e->nombre }}</h6>
                    <div class="text-muted small">{{ $e->nombre }}</div>
                    <code class="small">RUC {{ $e->ruc_completo }}</code>
                </div>
            </div>
            <div class="row g-1 small text-muted mb-3">
                <div class="col-6"><i class="bi bi-geo-alt me-1"></i>{{ $e->ciudad ?: '—' }}</div>
                <div class="col-6"><i class="bi bi-building me-1"></i>{{ $e->sucursales_count }} sucursal(es)</div>
                <div class="col-6"><i class="bi bi-currency-exchange me-1"></i>{{ $e->moneda }} ({{ $e->simbolo }})</div>
                <div class="col-6">
                    @if($e->fact_modo === 'electronico')
                    <span class="badge bg-success">Electrónica</span>
                    @else
                    <span class="badge bg-warning text-dark">Demo</span>
                    @endif
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('empresas.show',$e) }}" class="btn btn-sm btn-primary flex-grow-1"><i class="bi bi-eye me-1"></i>Gestionar</a>
                <a href="{{ route('empresas.edit',$e) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
            </div>
        </div>
    </div>
</div>
@empty
<div class="col-12"><div class="alert alert-info">Sin empresas registradas.</div></div>
@endforelse
</div>
@endsection
