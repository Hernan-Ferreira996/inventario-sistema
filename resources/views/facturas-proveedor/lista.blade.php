@extends('layouts.app')
@section('titulo','Facturas de Proveedor')
@section('contenido')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Facturas de Proveedor</h5>
    @can('facturas_proveedor.crear')
    @if(!Auth::user()?->esSuperAdmin())
    <a href="{{ route('facturas-proveedor.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Nueva Factura de Proveedor</a>
    @endif
    @endcan
</div>

<div class="card mb-3"><div class="card-body py-2">
<form method="GET" class="row g-2 align-items-center">
    <div class="col-md-5"><input type="text" name="q" class="form-control" placeholder="Buscar por N° o proveedor..." value="{{ request('q') }}"></div>
    <div class="col-md-3">
        <select name="estado" class="form-select">
            <option value="">Todos los estados</option>
            @foreach(\App\Models\CatalogoValor::codigos('facturas_proveedor.estado') as $e)
            <option value="{{ $e }}" {{ request('estado')===$e ? 'selected' : '' }}>{{ \App\Models\CatalogoValor::etiqueta('facturas_proveedor.estado', $e) }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2"><button type="submit" class="btn btn-outline-primary w-100"><i class="bi bi-search me-1"></i>Filtrar</button></div>
    <div class="col-md-2"><a href="{{ route('facturas-proveedor.index') }}" class="btn btn-outline-secondary w-100">Limpiar</a></div>
</form>
</div></div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light"><tr>
                <th>N° Interno</th><th>N° Factura</th><th>Proveedor</th><th>Fecha</th><th>Vencimiento</th><th class="text-end">Total</th><th class="text-end">Saldo</th><th class="text-center">Estado</th><th>Acciones</th>
            </tr></thead>
            <tbody>
            @forelse($facturas as $f)
            <tr>
                <td class="fw-semibold"><a href="{{ route('facturas-proveedor.show',$f) }}" class="text-decoration-none">{{ $f->numero_referencia }}</a></td>
                <td>{{ $f->numero_factura_proveedor }}</td>
                <td>{{ $f->proveedor->nombre ?? '—' }}</td>
                <td>{{ $f->fecha_emision->format('d/m/Y') }}</td>
                <td>{{ $f->fecha_vencimiento?->format('d/m/Y') ?? '—' }}</td>
                <td class="text-end fw-semibold">{{ number_format($f->total,0,',','.') }}</td>
                <td class="text-end">{{ number_format($f->saldo_pendiente,0,',','.') }}</td>
                <td class="text-center"><x-badge-estado grupo="facturas_proveedor.estado" :valor="$f->estado" /></td>
                <td><div class="d-flex gap-1">
                    <a href="{{ route('facturas-proveedor.show',$f) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                    @can('facturas_proveedor.editar')
                    @if($f->estado === 'pendiente' && (float)$f->monto_pagado <= 0)
                    <a href="{{ route('facturas-proveedor.edit',$f) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                    @endif
                    @endcan
                </div></td>
            </tr>
            @empty
            <tr><td colspan="9" class="text-center py-5 text-muted">
                <i class="bi bi-file-earmark-text d-block mb-2" style="font-size:2rem"></i>
                Sin facturas de proveedor. @if(!Auth::user()?->esSuperAdmin())<a href="{{ route('facturas-proveedor.create') }}">Crear la primera</a>@endif
            </td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($facturas->hasPages())<div class="card-footer py-2">{{ $facturas->links() }}</div>@endif
</div>
@endsection
