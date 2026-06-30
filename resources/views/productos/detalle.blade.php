@extends('layouts.app')
@section('titulo', $producto->nombre)
@section('contenido')
<div class="row g-3">
<div class="col-md-4">
<div class="card mb-3"><div class="card-body text-center p-4">
@if($producto->imagen)
<img src="{{ Storage::url($producto->imagen) }}" class="img-fluid rounded mb-3" style="max-height:180px;object-fit:contain">
@else
<div class="bg-light rounded d-flex align-items-center justify-content-center mb-3" style="height:120px"><i class="bi bi-image text-secondary" style="font-size:3rem"></i></div>
@endif
<h5 class="fw-bold">{{ $producto->nombre }}</h5>
<code class="text-primary">{{ $producto->codigo }}</code>
<p class="text-muted mt-2 mb-0">{{ $producto->descripcion }}</p>
</div></div>
<div class="card mb-3"><div class="card-header">Precios</div><div class="list-group list-group-flush">
<div class="list-group-item d-flex justify-content-between"><span class="text-muted">Compra</span><strong>$ {{ number_format($producto->precio_compra,2) }}</strong></div>
<div class="list-group-item d-flex justify-content-between"><span class="text-muted">Minorista</span><strong class="text-success">$ {{ number_format($producto->precio_venta_minorista,2) }}</strong></div>
<div class="list-group-item d-flex justify-content-between"><span class="text-muted">Mayorista</span><strong class="text-primary">$ {{ number_format($producto->precio_venta_mayorista,2) }}</strong></div>
</div></div>
<div class="d-grid gap-2"><a href="{{ route('productos.edit',$producto) }}" class="btn btn-primary"><i class="bi bi-pencil me-1"></i>Editar</a><a href="{{ route('productos.index') }}" class="btn btn-outline-secondary">Volver a lista</a></div>
</div>
<div class="col-md-8">
<div class="card mb-3"><div class="card-header">Stock por Ubicacion</div>
<div class="table-responsive"><table class="table mb-0"><thead><tr><th>Ubicacion</th><th class="text-end">Cantidad</th></tr></thead>
<tbody>
@forelse($stockPorUbicacion as $s)
<tr><td>{{ $s->ubicacion?->nombre ?? 'Desconocida' }}</td><td class="text-end fw-semibold {{ $s->total <= 0 ? 'text-danger' : 'text-success' }}">{{ number_format($s->total,2) }}</td></tr>
@empty<tr><td colspan="2" class="text-center text-muted py-3">Sin movimientos de stock</td></tr>
@endforelse
</tbody></table></div></div>
<div class="card"><div class="card-header d-flex justify-content-between align-items-center">
<span>Historial de Movimientos</span>
<button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#modalStock"><i class="bi bi-plus-lg me-1"></i>Ajustar Stock</button>
</div>
<div class="table-responsive"><table class="table table-sm mb-0"><thead><tr><th>Fecha</th><th>Tipo</th><th>Ubicacion</th><th class="text-end">Cantidad</th><th>Referencia</th></tr></thead>
<tbody>
@forelse($historialMovimientos as $m)
<tr>
<td>{{ $m->fecha_movimiento->format('d/m/Y H:i') }}</td>
<td><span class="badge {{ $m->tipo == 'entrada' ? 'bg-success' : ($m->tipo == 'salida' ? 'bg-danger' : 'bg-warning text-dark') }}">{{ ucfirst($m->tipo) }}</span></td>
<td>{{ $m->ubicacion?->nombre ?? '—' }}</td>
<td class="text-end fw-semibold {{ $m->cantidad < 0 ? 'text-danger' : 'text-success' }}">{{ $m->cantidad > 0 ? '+' : '' }}{{ number_format($m->cantidad,2) }}</td>
<td>{{ $m->referencia ?? '—' }}</td>
</tr>
@empty<tr><td colspan="5" class="text-center text-muted py-3">Sin movimientos</td></tr>
@endforelse
</tbody></table></div></div>
</div>
</div>

<!-- Modal ajuste de stock -->
<div class="modal fade" id="modalStock" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
<div class="modal-header"><h5 class="modal-title">Ajustar Stock — {{ $producto->nombre }}</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
<form method="POST" action="{{ route('productos.stock',$producto) }}">@csrf
<div class="modal-body">
<div class="mb-3"><label class="form-label fw-semibold">Ubicacion *</label>
<select name="ubicacion_id" class="form-select" required>
@foreach(\App\Models\Ubicacion::where('activo',true)->get() as $u)
<option value="{{ $u->id }}">{{ $u->nombre }}</option>
@endforeach
</select></div>
<div class="mb-3"><label class="form-label fw-semibold">Tipo *</label>
<select name="tipo" class="form-select"><option value="entrada">Entrada</option><option value="salida">Salida</option><option value="ajuste">Ajuste</option></select></div>
<div class="mb-3"><label class="form-label fw-semibold">Cantidad *</label><input type="number" name="cantidad" class="form-control" step="0.01" min="0.01" required></div>
<div class="mb-3"><label class="form-label fw-semibold">Referencia</label><input type="text" name="referencia" class="form-control" placeholder="Ej: Compra #001"></div>
<div class="mb-3"><label class="form-label fw-semibold">Notas</label><textarea name="notas" class="form-control" rows="2"></textarea></div>
</div>
<div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button><button type="submit" class="btn btn-success">Guardar Movimiento</button></div>
</form>
</div></div></div>
@endsection