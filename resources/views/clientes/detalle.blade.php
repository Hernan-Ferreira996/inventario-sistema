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
@if($cliente->ciudad)<p class="text-muted mb-0 small"><i class="bi bi-geo-alt me-1"></i>{{ $cliente->ciudad->nombre_completo }}</p>@endif
@if($etiquetas->isNotEmpty())
<div class="mt-2">
@foreach($etiquetas as $etq)
<span class="badge me-1" style="background:{{ $etq->color }}">{{ $etq->nombre }}</span>
@endforeach
</div>
@endif
@if($campos->isNotEmpty())
<hr>
@foreach($campos as $campo)
<p class="text-muted mb-1 small"><strong>{{ $campo->etiqueta }}:</strong> {{ $valoresCamposPersonalizados[$campo->nombre] ?? '—' }}</p>
@endforeach
@endif
@if($cliente->expuesto_publicamente || $cliente->funcionario || $cliente->exento_iva)
<hr>
@if($cliente->expuesto_publicamente)<span class="badge bg-warning text-dark me-1">PEP</span>@endif
@if($cliente->funcionario)<span class="badge bg-info text-dark me-1">Funcionario público</span>@endif
@if($cliente->exento_iva)<span class="badge bg-secondary">Exento IVA</span>@endif
@endif
@if($cliente->limite_credito)
<hr>
@php $excedido = $resumen['saldo_pendiente'] > $cliente->limite_credito; @endphp
<p class="mb-1 small"><strong>Límite de Crédito:</strong> {{ number_format($cliente->limite_credito,0,',','.') }}</p>
@if($excedido)
<div class="alert alert-danger py-1 px-2 small mb-0"><i class="bi bi-exclamation-triangle me-1"></i>Saldo pendiente supera el límite de crédito.</div>
@endif
@endif
<hr>
<div class="d-flex gap-2">
<a href="{{ route('clientes.edit',$cliente) }}" class="btn btn-sm btn-primary">Editar</a>
<a href="{{ route('clientes.index') }}" class="btn btn-sm btn-outline-secondary">Volver</a>
</div>
</div></div>

<div class="card mt-3">
<div class="card-header d-flex justify-content-between align-items-center">
    <span class="fw-semibold">Contactos</span>
    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalContacto"><i class="bi bi-plus-lg"></i></button>
</div>
<div class="list-group list-group-flush">
@forelse($contactos as $c)
<div class="list-group-item d-flex justify-content-between align-items-start">
    <div>
        <strong>{{ $c->nombre }}</strong> @if($c->es_principal)<span class="badge bg-primary ms-1">Principal</span>@endif
        <div class="text-muted small">{{ $c->cargo }}</div>
        <div class="text-muted small">{{ $c->telefono }} {{ $c->email ? '· '.$c->email : '' }}</div>
    </div>
    <form method="POST" action="{{ route('contactos.destroy',$c) }}" onsubmit="return confirm('¿Eliminar contacto?')">@csrf @method('DELETE')
        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
    </form>
</div>
@empty
<div class="list-group-item text-muted small">Sin contactos registrados.</div>
@endforelse
</div>
</div>

<div class="card mt-3">
<div class="card-header fw-semibold">Documentos Adjuntos</div>
<div class="list-group list-group-flush">
@forelse($documentos as $d)
<div class="list-group-item d-flex justify-content-between align-items-center">
    <a href="{{ route('documentos.download',$d) }}" class="text-decoration-none"><i class="bi bi-paperclip me-1"></i>{{ $d->nombre_archivo }}</a>
    <form method="POST" action="{{ route('documentos.destroy',$d) }}" onsubmit="return confirm('¿Eliminar documento?')">@csrf @method('DELETE')
        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
    </form>
</div>
@empty
<div class="list-group-item text-muted small">Sin documentos adjuntos.</div>
@endforelse
</div>
<div class="card-body py-2">
<form method="POST" action="{{ route('clientes.documentos.store',$cliente) }}" enctype="multipart/form-data" class="d-flex gap-2">
    @csrf
    <input type="file" name="archivo" class="form-control form-control-sm" required>
    <button class="btn btn-sm btn-outline-primary"><i class="bi bi-upload"></i></button>
</form>
</div>
</div>
</div>

<div class="col-md-8">
<div class="card"><div class="card-header d-flex justify-content-between align-items-center">
    <span>Resumen</span>
    <x-badge-estado grupo="clientes.nivel_moroso" :valor="$cliente->nivelMoroso()" />
</div><div class="card-body">
<div class="row text-center g-3">
<div class="col"><div class="fw-bold fs-4">{{ $resumen['total_pedidos'] }}</div><div class="text-muted small">Pedidos</div></div>
<div class="col"><div class="fw-bold fs-4">{{ number_format($resumen['total_comprado'],2) }}</div><div class="text-muted small">Total Comprado</div></div>
<div class="col"><div class="fw-bold fs-4 text-danger">{{ number_format($resumen['saldo_pendiente'] ?? 0,2) }}</div><div class="text-muted small">Saldo Pendiente</div></div>
</div>
</div></div>

<div class="card mt-3">
<div class="card-header d-flex justify-content-between align-items-center">
    <span class="fw-semibold">Interacciones</span>
    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalInteraccion"><i class="bi bi-plus-lg"></i>Nueva</button>
</div>
<div class="list-group list-group-flush">
@forelse($interacciones as $i)
<div class="list-group-item d-flex justify-content-between align-items-start">
    <div>
        <x-badge-estado grupo="interacciones.tipo" :valor="$i->tipo" />
        <span class="ms-2">{{ $i->descripcion }}</span>
        <div class="text-muted small">{{ $i->fecha->format('d/m/Y H:i') }} · {{ $i->usuario->name ?? '—' }}</div>
    </div>
    <form method="POST" action="{{ route('interacciones.destroy',$i) }}" onsubmit="return confirm('¿Eliminar interacción?')">@csrf @method('DELETE')
        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
    </form>
</div>
@empty
<div class="list-group-item text-muted small">Sin interacciones registradas.</div>
@endforelse
</div>
</div>

<div class="card mt-3"><div class="card-header">Últimos Pedidos</div>
<div class="table-responsive"><table class="table mb-0">
<thead><tr><th>Referencia</th><th>Fecha</th><th class="text-end">Total</th><th>Estado</th></tr></thead>
<tbody>
@forelse($cliente->pedidos as $p)
<tr>
<td><a href="{{ route('pedidos.show',$p) }}">{{ $p->numero_referencia }}</a></td>
<td>{{ $p->fecha_pedido->format('d/m/Y') }}</td>
<td class="text-end">{{ number_format($p->total,2) }}</td>
<td><x-badge-estado grupo="pedidos_venta.estado" :valor="$p->estado" /></td>
</tr>
@empty
<tr><td colspan="4" class="text-center text-muted py-3">Sin pedidos</td></tr>
@endforelse
</tbody></table></div></div>

<div class="card mt-3">
<div class="card-header fw-semibold">Línea de Tiempo</div>
<div class="list-group list-group-flush">
@forelse($lineaDeTiempo as $ev)
<div class="list-group-item">
    <i class="bi {{ $ev['icono'] }} me-2 text-primary"></i>
    <span class="badge bg-light text-dark border me-2">{{ $ev['tipo'] }}</span>
    {{ $ev['texto'] }}
    <span class="text-muted small float-end">{{ \Carbon\Carbon::parse($ev['fecha'])->format('d/m/Y') }}</span>
</div>
@empty
<div class="list-group-item text-muted small">Sin actividad registrada.</div>
@endforelse
</div>
</div>
</div>
</div>

<div class="modal fade" id="modalContacto" tabindex="-1">
<div class="modal-dialog"><div class="modal-content">
<div class="modal-header"><h5 class="modal-title">Nuevo Contacto</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
<form method="POST" action="{{ route('clientes.contactos.store',$cliente) }}">@csrf
<div class="modal-body">
    <div class="row g-3">
        <div class="col-12"><label class="form-label fw-semibold">Nombre *</label><input type="text" name="nombre" class="form-control" required></div>
        <div class="col-md-6"><label class="form-label fw-semibold">Cargo</label><input type="text" name="cargo" class="form-control"></div>
        <div class="col-md-6"><label class="form-label fw-semibold">Teléfono</label><input type="text" name="telefono" class="form-control"></div>
        <div class="col-md-8"><label class="form-label fw-semibold">Email</label><input type="email" name="email" class="form-control"></div>
        <div class="col-md-4 d-flex align-items-end"><div class="form-check"><input type="checkbox" name="es_principal" value="1" class="form-check-input"><label class="form-check-label">Principal</label></div></div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
    <button type="submit" class="btn btn-primary">Guardar</button>
</div>
</form>
</div></div>
</div>

<div class="modal fade" id="modalInteraccion" tabindex="-1">
<div class="modal-dialog"><div class="modal-content">
<div class="modal-header"><h5 class="modal-title">Nueva Interacción</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
<form method="POST" action="{{ route('clientes.interacciones.store',$cliente) }}">@csrf
<div class="modal-body">
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label fw-semibold">Tipo *</label>
            <select name="tipo" class="form-select" required>
                @foreach(\App\Models\CatalogoValor::paraGrupo('interacciones.tipo') as $t)
                <option value="{{ $t->codigo }}">{{ $t->etiqueta }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6"><label class="form-label fw-semibold">Fecha *</label><input type="datetime-local" name="fecha" class="form-control" value="{{ now()->format('Y-m-d\TH:i') }}" required></div>
        <div class="col-12"><label class="form-label fw-semibold">Descripción *</label><textarea name="descripcion" class="form-control" rows="3" required></textarea></div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
    <button type="submit" class="btn btn-primary">Guardar</button>
</div>
</form>
</div></div>
</div>
@endsection
