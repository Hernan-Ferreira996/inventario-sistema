@extends('layouts.app')
@section('titulo','Clientes')
@section('contenido')
<div class="d-flex justify-content-between mb-3">
<h5>Clientes</h5>
<a href="{{ route('clientes.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Nuevo Cliente</a>
</div>
<div class="card mb-3"><div class="card-body py-2">
<form method="GET" class="row g-2">
    <div class="col-md-6"><input type="text" name="q" class="form-control" placeholder="Buscar por nombre, email o RUC..." value="{{ request('q') }}"></div>
    <div class="col-md-3">
        <select name="tipo_precio" class="form-select">
            <option value="">Todos los tipos</option>
            <option value="minorista" {{ request('tipo_precio')==='minorista' ? 'selected':'' }}>Minorista</option>
            <option value="mayorista" {{ request('tipo_precio')==='mayorista' ? 'selected':'' }}>Mayorista</option>
        </select>
    </div>
    <div class="col-md-2"><button class="btn btn-outline-primary w-100"><i class="bi bi-search me-1"></i>Filtrar</button></div>
    <div class="col-md-1">@if(request()->hasAny(['q','tipo_precio']))<a href="{{ route('clientes.index') }}" class="btn btn-outline-danger w-100"><i class="bi bi-x-lg"></i></a>@endif</div>
</form>
</div></div>
<div class="card"><div class="table-responsive"><table class="table table-hover mb-0 align-middle">
<thead><tr><th>Nombre</th><th>Email</th><th>Telefono</th><th>Tipo</th><th>Pedidos</th><th>Estado</th><th>Acciones</th></tr></thead>
<tbody>
@forelse($clientes as $c)
<tr>
<td class="fw-semibold">{{ $c->nombre }}</td>
<td>{{ $c->email ?? '—' }}</td>
<td>{{ $c->telefono ?? '—' }}</td>
<td><span class="badge {{ $c->tipo_precio == 'mayorista' ? 'bg-primary' : 'bg-secondary' }}">{{ ucfirst($c->tipo_precio) }}</span></td>
<td>{{ $c->pedidos_count }}</td>
<td>@if($c->activo)<span class="badge bg-success">Activo</span>@else<span class="badge bg-secondary">Inactivo</span>@endif</td>
<td>
<a href="{{ route('clientes.show',$c) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
<a href="{{ route('clientes.edit',$c) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
<form method="POST" action="{{ route('clientes.destroy',$c) }}" class="d-inline" onsubmit="return confirm('Eliminar cliente?')">@csrf @method('DELETE')
<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form>
</td>
</tr>
@empty
<tr><td colspan="7" class="text-center py-4 text-muted">Sin clientes. <a href="{{ route('clientes.create') }}">Agregar primero</a></td></tr>
@endforelse
</tbody></table></div>
@if($clientes->hasPages())<div class="card-footer py-2">{{ $clientes->links() }}</div>@endif
</div>
@endsection