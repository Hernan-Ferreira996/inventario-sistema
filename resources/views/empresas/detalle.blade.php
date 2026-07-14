@extends('layouts.app')
@section('titulo', $empresa->nombre_fantasia ?: $empresa->nombre)
@section('contenido')
<div class="row g-3">
<div class="col-md-4">
    <div class="card mb-3">
        <div class="card-header fw-semibold">Datos de la Empresa</div>
        <div class="list-group list-group-flush">
            <div class="list-group-item"><small class="text-muted d-block">Razón Social</small><strong>{{ $empresa->nombre }}</strong></div>
            @if($empresa->nombre_fantasia)<div class="list-group-item"><small class="text-muted d-block">Nombre Fantasía</small>{{ $empresa->nombre_fantasia }}</div>@endif
            <div class="list-group-item"><small class="text-muted d-block">RUC</small><code>{{ $empresa->ruc_completo }}</code></div>
            <div class="list-group-item"><small class="text-muted d-block">Ciudad / País</small>{{ $empresa->ciudad }}, {{ $empresa->pais }}</div>
            <div class="list-group-item"><small class="text-muted d-block">Moneda</small>{{ $empresa->moneda }} ({{ $empresa->simbolo }})</div>
            <div class="list-group-item"><small class="text-muted d-block">Timbrado</small>
                {{ $empresa->fact_timbrado ?: 'Sin asignar' }}
                @if($empresa->fact_modo === 'electronico')
                <span class="badge bg-success ms-1">Electrónico</span>
                @else
                <span class="badge bg-warning text-dark ms-1">Demo</span>
                @endif
            </div>
        </div>
    </div>
    <div class="card mb-3">
        <div class="card-header fw-semibold">Usuarios de esta Empresa</div>
        <div class="list-group list-group-flush">
        @forelse($usuarios as $u)
        <div class="list-group-item d-flex justify-content-between">
            <span>{{ $u->name }}</span>
            <small class="text-muted">{{ $u->roles->pluck('name')->implode(', ') }}</small>
        </div>
        @empty
        <div class="list-group-item text-muted small">Sin usuarios asignados</div>
        @endforelse
        </div>
    </div>
    <div class="d-grid gap-2">
        <a href="{{ route('empresas.edit',$empresa) }}" class="btn btn-outline-warning"><i class="bi bi-pencil me-1"></i>Editar Empresa</a>
        <a href="{{ route('empresas.index') }}" class="btn btn-outline-secondary">Volver a lista</a>
    </div>
</div>
<div class="col-md-8">
    <div class="card mb-3">
        <div class="card-header fw-semibold"><i class="bi bi-box-seam me-2 text-primary"></i>Plan y Módulos Contratados</div>
        <div class="card-body">
        <form method="POST" action="{{ route('empresas.modulos', $empresa) }}">@csrf
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Plan</label>
                    <select name="plan_id" class="form-select">
                        <option value="">Sin plan (solo módulos núcleo)</option>
                        @foreach($planes as $p)
                        <option value="{{ $p->id }}" {{ $empresa->plan_id == $p->id ? 'selected' : '' }}>{{ $p->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Vencimiento de Licencia</label>
                    <input type="date" name="fecha_vencimiento_licencia" class="form-control"
                        value="{{ $empresa->fecha_vencimiento_licencia?->format('Y-m-d') }}">
                    <small class="text-muted">Vacío = sin vencimiento</small>
                </div>
            </div>
            <table class="table table-sm align-middle mb-3">
                <thead><tr><th>Módulo</th><th class="text-center">Según Plan</th><th class="text-center">Forzar Activo</th><th class="text-center">Forzar Inactivo</th></tr></thead>
                <tbody>
                @foreach($modulos as $m)
                @php $excepcion = $excepciones->get($m->id); $estado = $excepcion ? ($excepcion->habilitado ? 'activo' : 'inactivo') : 'plan'; @endphp
                <tr>
                    <td>{{ $m->nombre }} @if($m->nucleo)<span class="badge bg-secondary ms-1">Núcleo</span>@endif</td>
                    <td class="text-center"><input type="radio" name="modulos[{{ $m->id }}]" value="plan" {{ $estado === 'plan' ? 'checked' : '' }} {{ $m->nucleo ? 'disabled' : '' }}></td>
                    <td class="text-center"><input type="radio" name="modulos[{{ $m->id }}]" value="activo" {{ $estado === 'activo' ? 'checked' : '' }} {{ $m->nucleo ? 'disabled' : '' }}></td>
                    <td class="text-center"><input type="radio" name="modulos[{{ $m->id }}]" value="inactivo" {{ $estado === 'inactivo' ? 'checked' : '' }} {{ $m->nucleo ? 'disabled' : '' }}></td>
                </tr>
                @endforeach
                </tbody>
            </table>
            <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Guardar Plan y Módulos</button>
        </form>
        </div>
    </div>
</div>
<div class="col-md-8">
    <div class="card mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span class="fw-semibold"><i class="bi bi-building me-2 text-primary"></i>Sucursales y Depósitos</span>
            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalSucursal">
                <i class="bi bi-plus-lg me-1"></i>Nueva Sucursal
            </button>
        </div>
        @foreach($empresa->sucursales as $suc)
        <div class="card-body border-bottom py-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div>
                    <h6 class="fw-bold mb-0">{{ $suc->nombre }}
                        @if($suc->principal)<span class="badge bg-primary ms-1">Principal</span>@endif
                    </h6>
                    <small class="text-muted">Establecimiento: <code>{{ $suc->codigo }}</code> | {{ $suc->ciudad }}</small>
                </div>
                <span class="badge {{ $suc->activo ? 'bg-success' : 'bg-secondary' }}">{{ $suc->activo ? 'Activa' : 'Inactiva' }}</span>
            </div>
            @if($suc->depositos->isNotEmpty())
            <div class="table-responsive">
            <table class="table table-sm mb-0">
                <thead><tr><th>Depósito</th><th>Código</th><th>Estado</th></tr></thead>
                <tbody>
                @foreach($suc->depositos as $dep)
                <tr>
                    <td>{{ $dep->nombre }}</td>
                    <td><code>{{ $dep->codigo }}</code></td>
                    <td><span class="badge {{ $dep->activo ? 'bg-success' : 'bg-secondary' }}">{{ $dep->activo ? 'Activo' : 'Inactivo' }}</span></td>
                </tr>
                @endforeach
                </tbody>
            </table>
            </div>
            @else
            <p class="text-muted small mb-0">Sin depósitos asignados a esta sucursal</p>
            @endif
        </div>
        @endforeach
        @if($empresa->sucursales->isEmpty())
        <div class="card-body text-center text-muted py-4">Sin sucursales registradas</div>
        @endif
    </div>
</div>
</div>
<div class="modal fade" id="modalSucursal" tabindex="-1">
<div class="modal-dialog"><div class="modal-content">
<div class="modal-header"><h5 class="modal-title">Nueva Sucursal</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
<form method="POST" action="{{ route('empresas.sucursales.store',$empresa) }}">@csrf
<div class="modal-body">
    <div class="row g-3">
        <div class="col-md-4"><label class="form-label fw-semibold">Código Establecimiento *</label>
            <input type="text" name="codigo" class="form-control" maxlength="3" placeholder="002" required></div>
        <div class="col-md-8"><label class="form-label fw-semibold">Nombre *</label>
            <input type="text" name="nombre" class="form-control" required placeholder="Sucursal Norte"></div>
        <div class="col-md-6"><label class="form-label fw-semibold">Ciudad</label>
            <input type="text" name="ciudad" class="form-control"></div>
        <div class="col-md-6"><label class="form-label fw-semibold">Teléfono</label>
            <input type="text" name="telefono" class="form-control"></div>
        <div class="col-12"><label class="form-label fw-semibold">Dirección</label>
            <input type="text" name="direccion" class="form-control"></div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Crear Sucursal</button>
</div>
</form>
</div></div>
</div>
@endsection
