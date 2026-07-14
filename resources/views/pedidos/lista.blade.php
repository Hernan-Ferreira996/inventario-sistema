@extends('layouts.app')
@section('titulo','Pedidos de Venta')
@section('contenido')
<div class="d-flex justify-content-between mb-3">
    <h5>Pedidos de Venta</h5>
    @if(!Auth::user()?->esSuperAdmin())
    <a href="{{ route('pedidos.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Nuevo Pedido</a>
    @endif
</div>
<div class="card mb-3"><div class="card-body py-2">
<form method="GET" class="row g-2 align-items-center">
    <div class="col-md-3"><input type="text" name="q" class="form-control" placeholder="N° referencia o cliente..." value="{{ request('q') }}"></div>
    <div class="col-md-2">
        <select name="estado" class="form-select">
            <option value="">Estado: todos</option>
            <option value="activo" {{ request('estado')==='activo' ? 'selected':'' }}>Activo</option>
            <option value="completado" {{ request('estado')==='completado' ? 'selected':'' }}>Completado</option>
            <option value="cancelado" {{ request('estado')==='cancelado' ? 'selected':'' }}>Cancelado</option>
        </select>
    </div>
    <div class="col-md-2">
        <select name="estado_factura" class="form-select">
            <option value="">Factura: todas</option>
            <option value="pendiente" {{ request('estado_factura')==='pendiente' ? 'selected':'' }}>Pendiente</option>
            <option value="parcial" {{ request('estado_factura')==='parcial' ? 'selected':'' }}>Parcial</option>
            <option value="completado" {{ request('estado_factura')==='completado' ? 'selected':'' }}>Completado</option>
        </select>
    </div>
    <div class="col-md-2"><input type="date" name="desde" class="form-control" value="{{ request('desde') }}" title="Desde"></div>
    <div class="col-md-2"><input type="date" name="hasta" class="form-control" value="{{ request('hasta') }}" title="Hasta"></div>
    <div class="col-md-1 d-flex gap-1">
        <button class="btn btn-outline-primary"><i class="bi bi-search"></i></button>
        @if(request()->hasAny(['q','estado','estado_factura','desde','hasta']))
        <a href="{{ route('pedidos.index') }}" class="btn btn-outline-danger"><i class="bi bi-x-lg"></i></a>
        @endif
    </div>
</form>
</div></div>
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead>
                <tr>
                    <th>Referencia</th><th>Cliente</th><th>Fecha</th>
                    <th class="text-end">Total</th><th class="text-end">Pagado</th>
                    <th>Factura</th><th>Estado</th><th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pedidos as $p)
                <tr>
                    <td class="fw-semibold"><a href="{{ route('pedidos.show',$p) }}" class="text-decoration-none">{{ $p->numero_referencia }}</a></td>
                    <td>{{ $p->cliente?->nombre ?? '—' }}</td>
                    <td>{{ $p->fecha_pedido->format('d/m/Y') }}</td>
                    <td class="text-end fw-semibold">{{ number_format($p->total,2) }}</td>
                    <td class="text-end text-success">{{ number_format($p->monto_pagado,2) }}</td>
                    <td><x-badge-estado grupo="pedidos_venta.estado_factura" :valor="$p->estado_factura" /></td>
                    <td><x-badge-estado grupo="pedidos_venta.estado" :valor="$p->estado" /></td>
                    <td>
                        <a href="{{ route('pedidos.show',$p) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                        <form method="POST" action="{{ route('pedidos.destroy',$p) }}" class="d-inline" onsubmit="return confirm('¿Eliminar pedido?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-5 text-muted">
                    <i class="bi bi-cart3 d-block mb-2" style="font-size:2rem"></i>
                    Sin pedidos registrados. @if(!Auth::user()?->esSuperAdmin())<a href="{{ route('pedidos.create') }}">Crear primero</a>@endif
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($pedidos->hasPages())
    <div class="card-footer py-2">{{ $pedidos->links() }}</div>
    @endif
</div>
@endsection
