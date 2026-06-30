@extends('layouts.app')
@section('titulo', $cliente->nombre)
@section('contenido')
<div class="row g-3">
<div class="col-md-4">
<div class="card"><div class="card-body">
<h5 class="fw-bold">{{ $cliente->nombre }}</h5>
<p class="text-muted mb-1">{{ $cliente->email ?? '—' }}</p>
<p class="text-muted mb-1"><i class="bi bi-phone me-1"></i>{{ $cliente->telefono ?? '—' }}</p>
<p class="text-muted mb-0">{{ $cliente->direccion ?? '—' }}</p>
<hr>
<div class="d-flex gap-2">
<a href="{{ route('clientes.edit',$cliente) }}" class="btn btn-sm btn-primary">Editar</a>
<a href="{{ route('clientes.index') }}" class="btn btn-sm btn-outline-secondary">Volver</a>
</div>
</div></div>
</div>
<div class="col-md-8">
<div class="card"><div class="card-header">Resumen</div><div class="card-body">
<div class="row text-center g-3">
<div class="col"><div class="fw-bold fs-4">{{ $resumen['total_pedidos'] }}</div><div class="text-muted small">Pedidos</div></div>
<div class="col"><div class="fw-bold fs-4">{{ number_format($resumen['total_comprado'],2) }}</div><div class="text-muted small">Total Comprado</div></div>
<div class="col"><div class="fw-bold fs-4 text-danger">{{ number_format($resumen['saldo_pendiente'] ?? 0,2) }}</div><div class="text-muted small">Saldo Pendiente</div></div>
</div>
</div></div>
<div class="card mt-3"><div class="card-header">Ultimos Pedidos</div>
<div class="table-responsive"><table class="table mb-0">
<thead><tr><th>Referencia</th><th>Fecha</th><th class="text-end">Total</th><th>Estado</th></tr></thead>
<tbody>
@forelse($cliente->pedidos as $p)
<tr>
<td><a href="{{ route('pedidos.show',$p) }}">{{ $p->numero_referencia }}</a></td>
<td>{{ $p->fecha_pedido->format('d/m/Y') }}</td>
<td class="text-end">{{ number_format($p->total,2) }}</td>
<td><span class="badge badge-estado-{{ $p->estado }}">{{ ucfirst($p->estado) }}</span></td>
</tr>
@empty
<tr><td colspan="4" class="text-center text-muted py-3">Sin pedidos</td></tr>
@endforelse
</tbody></table></div></div>
</div>
</div>
@endsection