@extends('layouts.app')
@section('titulo','Proveedores')
@section('contenido')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Proveedores</h5>
    <a href="{{ route('proveedores.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Nuevo Proveedor</a>
</div>
<div class="card mb-3"><div class="card-body py-2">
<form method="GET" class="row g-2">
    <div class="col-md-8"><input type="text" name="q" class="form-control" placeholder="Buscar por nombre, email o RUC..." value="{{ request('q') }}"></div>
    <div class="col-md-2"><button class="btn btn-outline-primary w-100"><i class="bi bi-search me-1"></i>Buscar</button></div>
    <div class="col-md-2">@if(request('q'))<a href="{{ route('proveedores.index') }}" class="btn btn-outline-danger w-100">Limpiar</a>@endif</div>
</form>
</div></div>
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light"><tr>
                <th>Nombre</th><th>Contacto</th><th>Teléfono</th><th>Email</th><th>RUC/NIT</th>
                <th class="text-center">Compras</th><th class="text-center">Estado</th><th>Acciones</th>
            </tr></thead>
            <tbody>
            @forelse($proveedores as $p)
            <tr>
                <td class="fw-semibold"><a href="{{ route('proveedores.show',$p) }}" class="text-decoration-none">{{ $p->nombre }}</a></td>
                <td>{{ $p->contacto ?? '—' }}</td>
                <td>{{ $p->telefono ?? '—' }}</td>
                <td>{{ $p->email ?? '—' }}</td>
                <td><code>{{ $p->ruc_nit ?? '—' }}</code></td>
                <td class="text-center"><span class="badge bg-secondary">{{ $p->pedidos_compra_count }}</span></td>
                <td class="text-center"><span class="badge {{ $p->activo ? 'bg-success' : 'bg-secondary' }}">{{ $p->activo ? 'Activo' : 'Inactivo' }}</span></td>
                <td><div class="d-flex gap-1">
                    <a href="{{ route('proveedores.show',$p) }}" class="btn btn-sm btn-outline-info" title="Ver"><i class="bi bi-eye"></i></a>
                    <a href="{{ route('proveedores.edit',$p) }}" class="btn btn-sm btn-outline-warning" title="Editar"><i class="bi bi-pencil"></i></a>
                    <form method="POST" action="{{ route('proveedores.destroy',$p) }}" onsubmit="return confirm('¿Eliminar proveedor?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                    </form>
                </div></td>
            </tr>
            @empty
            <tr><td colspan="8" class="text-center py-5 text-muted">
                <i class="bi bi-building d-block mb-2" style="font-size:2rem"></i>
                Sin proveedores. <a href="{{ route('proveedores.create') }}">Agregar el primero</a>
            </td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($proveedores->hasPages())<div class="card-footer py-2">{{ $proveedores->links() }}</div>@endif
</div>
@endsection
