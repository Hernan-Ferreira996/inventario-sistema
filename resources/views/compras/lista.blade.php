@extends('layouts.app')
@section('titulo','Pedidos de Compra')
@section('contenido')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Pedidos de Compra</h5>
    <a href="{{ route('compras.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Nueva Compra</a>
</div>
<div class="card mb-3"><div class="card-body py-2">
<form method="GET" class="row g-2">
    <div class="col-md-5"><input type="text" name="q" class="form-control" placeholder="N° referencia o proveedor..." value="{{ request('q') }}"></div>
    <div class="col-md-3">
        <select name="estado" class="form-select">
            <option value="">Estado: todos</option>
            <option value="pendiente" {{ request('estado')==='pendiente' ? 'selected':'' }}>Pendiente</option>
            <option value="parcial" {{ request('estado')==='parcial' ? 'selected':'' }}>Parcial</option>
            <option value="completado" {{ request('estado')==='completado' ? 'selected':'' }}>Completado</option>
            <option value="cancelado" {{ request('estado')==='cancelado' ? 'selected':'' }}>Cancelado</option>
        </select>
    </div>
    <div class="col-md-2"><button class="btn btn-outline-primary w-100"><i class="bi bi-search me-1"></i>Filtrar</button></div>
    <div class="col-md-2">@if(request()->hasAny(['q','estado']))<a href="{{ route('compras.index') }}" class="btn btn-outline-danger w-100">Limpiar</a>@endif</div>
</form>
</div></div>
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light"><tr>
                <th>Referencia</th><th>Proveedor</th><th>Fecha</th><th>Fecha Esperada</th>
                <th class="text-end">Total</th><th class="text-center">Estado</th><th>Acciones</th>
            </tr></thead>
            <tbody>
            @forelse($pedidos as $p)
            <tr>
                <td class="fw-semibold"><a href="{{ route('compras.show',$p) }}" class="text-decoration-none">{{ $p->numero_referencia }}</a></td>
                <td>{{ $p->proveedor?->nombre ?? '—' }}</td>
                <td>{{ $p->fecha_pedido->format('d/m/Y') }}</td>
                <td>{{ $p->fecha_esperada?->format('d/m/Y') ?? '—' }}</td>
                <td class="text-end fw-semibold">{{ number_format($p->total,2) }}</td>
                <td class="text-center"><span class="badge badge-estado-{{ $p->estado }}">{{ ucfirst($p->estado) }}</span></td>
                <td><div class="d-flex gap-1">
                    <a href="{{ route('compras.show',$p) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                    @if($p->estado === 'pendiente')
                    <a href="{{ route('compras.edit',$p) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                    <form method="POST" action="{{ route('compras.destroy',$p) }}" onsubmit="return confirm('¿Eliminar pedido?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                    </form>
                    @endif
                </div></td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center py-5 text-muted">
                <i class="bi bi-bag-check d-block mb-2" style="font-size:2rem"></i>
                Sin pedidos de compra. <a href="{{ route('compras.create') }}">Crear el primero</a>
            </td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($pedidos->hasPages())<div class="card-footer py-2">{{ $pedidos->links() }}</div>@endif
</div>
@endsection
