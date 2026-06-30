@extends('layouts.app')
@section('titulo', $proveedor->nombre)
@section('contenido')
<div class="row g-3">
<div class="col-md-4">
    <div class="card mb-3"><div class="card-header fw-semibold">Datos del Proveedor</div>
    <div class="list-group list-group-flush">
        <div class="list-group-item"><small class="text-muted d-block">Nombre</small><strong>{{ $proveedor->nombre }}</strong></div>
        <div class="list-group-item"><small class="text-muted d-block">Contacto</small>{{ $proveedor->contacto ?? '—' }}</div>
        <div class="list-group-item"><small class="text-muted d-block">Teléfono</small>{{ $proveedor->telefono ?? '—' }}</div>
        <div class="list-group-item"><small class="text-muted d-block">Email</small>{{ $proveedor->email ?? '—' }}</div>
        <div class="list-group-item"><small class="text-muted d-block">RUC / NIT</small><code>{{ $proveedor->ruc_nit ?? '—' }}</code></div>
        <div class="list-group-item"><small class="text-muted d-block">Dirección</small>{{ $proveedor->direccion ?? '—' }}</div>
        <div class="list-group-item"><small class="text-muted d-block">Estado</small>
            <span class="badge {{ $proveedor->activo ? 'bg-success' : 'bg-secondary' }}">{{ $proveedor->activo ? 'Activo' : 'Inactivo' }}</span>
        </div>
    </div></div>
    <div class="d-grid gap-2 mt-2">
        <a href="{{ route('proveedores.edit',$proveedor) }}" class="btn btn-primary"><i class="bi bi-pencil me-1"></i>Editar</a>
        <a href="{{ route('compras.create') }}" class="btn btn-outline-success"><i class="bi bi-bag-plus me-1"></i>Nueva Compra</a>
        <a href="{{ route('proveedores.index') }}" class="btn btn-outline-secondary">Volver a lista</a>
    </div>
</div>
<div class="col-md-8">
    <div class="card"><div class="card-header d-flex justify-content-between align-items-center">
        <span>Últimas Compras</span>
        <span class="badge bg-secondary">{{ $proveedor->pedidos_compra_count }} total</span>
    </div>
    <div class="table-responsive"><table class="table mb-0">
    <thead><tr><th>Referencia</th><th>Fecha</th><th class="text-end">Total</th><th>Estado</th><th></th></tr></thead>
    <tbody>
    @forelse($ultimas as $c)
    <tr>
        <td class="fw-semibold">{{ $c->numero_referencia }}</td>
        <td>{{ $c->fecha_pedido->format('d/m/Y') }}</td>
        <td class="text-end">{{ number_format($c->total,2) }}</td>
        <td><span class="badge badge-estado-{{ $c->estado }}">{{ ucfirst($c->estado) }}</span></td>
        <td><a href="{{ route('compras.show',$c) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a></td>
    </tr>
    @empty
    <tr><td colspan="5" class="text-center py-4 text-muted">Sin compras registradas</td></tr>
    @endforelse
    </tbody></table></div></div>
</div>
</div>
@endsection
