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
        <div class="list-group-item"><small class="text-muted d-block">Ciudad</small>{{ $proveedor->ciudad->nombre_completo ?? '—' }}</div>
        <div class="list-group-item"><small class="text-muted d-block">País</small>{{ $proveedor->pais ?? '—' }}</div>
        @if($proveedor->expuesto_publicamente || $proveedor->funcionario)
        <div class="list-group-item">
            @if($proveedor->expuesto_publicamente)<span class="badge bg-warning text-dark me-1">PEP</span>@endif
            @if($proveedor->funcionario)<span class="badge bg-info text-dark">Funcionario público</span>@endif
        </div>
        @endif
        <div class="list-group-item"><small class="text-muted d-block">Estado</small>
            <span class="badge {{ $proveedor->activo ? 'bg-success' : 'bg-secondary' }}">{{ $proveedor->activo ? 'Activo' : 'Inactivo' }}</span>
        </div>
        @if($etiquetas->isNotEmpty())
        <div class="list-group-item">
            @foreach($etiquetas as $etq)
            <span class="badge me-1" style="background:{{ $etq->color }}">{{ $etq->nombre }}</span>
            @endforeach
        </div>
        @endif
        @foreach($campos as $campo)
        <div class="list-group-item"><small class="text-muted d-block">{{ $campo->etiqueta }}</small>{{ $valoresCamposPersonalizados[$campo->nombre] ?? '—' }}</div>
        @endforeach
    </div></div>
    <div class="d-grid gap-2 mt-2">
        <a href="{{ route('proveedores.edit',$proveedor) }}" class="btn btn-primary"><i class="bi bi-pencil me-1"></i>Editar</a>
        @if(!Auth::user()?->esSuperAdmin())
        <a href="{{ route('compras.create') }}" class="btn btn-outline-success"><i class="bi bi-bag-plus me-1"></i>Nueva Compra</a>
        @endif
        <a href="{{ route('proveedores.index') }}" class="btn btn-outline-secondary">Volver a lista</a>
    </div>

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
    <form method="POST" action="{{ route('proveedores.documentos.store',$proveedor) }}" enctype="multipart/form-data" class="d-flex gap-2">
        @csrf
        <input type="file" name="archivo" class="form-control form-control-sm" required>
        <button class="btn btn-sm btn-outline-primary"><i class="bi bi-upload"></i></button>
    </form>
    </div>
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
        <td><x-badge-estado grupo="pedidos_compra.estado" :valor="$c->estado" /></td>
        <td><a href="{{ route('compras.show',$c) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a></td>
    </tr>
    @empty
    <tr><td colspan="5" class="text-center py-4 text-muted">Sin compras registradas</td></tr>
    @endforelse
    </tbody></table></div></div>

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
<form method="POST" action="{{ route('proveedores.contactos.store',$proveedor) }}">@csrf
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
<form method="POST" action="{{ route('proveedores.interacciones.store',$proveedor) }}">@csrf
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
