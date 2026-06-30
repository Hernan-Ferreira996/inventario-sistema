@extends('layouts.app')
@section('titulo','Permisos: ' . ucfirst($grupo->name))
@section('contenido')

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="mb-0">Grupo: <span class="text-primary">{{ ucfirst($grupo->name) }}</span></h5>
        <small class="text-muted">{{ $grupo->users->count() ?? 0 }} usuario(s) en este grupo</small>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('grupos.edit',$grupo) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil me-1"></i>Renombrar</a>
        <a href="{{ route('grupos.index') }}" class="btn btn-sm btn-outline-secondary">Volver a grupos</a>
    </div>
</div>

@if($permisosDesconocidos->isNotEmpty())
<div class="alert alert-warning d-flex align-items-center gap-3">
    <i class="bi bi-exclamation-triangle fs-4"></i>
    <div>
        <strong>Se detectaron {{ $permisosDesconocidos->count() }} módulo(s) nuevo(s)</strong> no incluidos en la matriz actual:
        <span class="badge bg-warning text-dark ms-1">{{ $permisosDesconocidos->pluck('name')->implode(', ') }}</span><br>
        <small>Estos permisos aparecen al final de la tabla para que puedas asignarlos al grupo.</small>
    </div>
</div>
@endif

<form method="POST" action="{{ route('grupos.permisos',$grupo) }}" id="formPermisos">
@csrf

<div class="card">
<div class="card-header d-flex justify-content-between align-items-center">
    <span class="fw-semibold">Matriz de acceso por módulo</span>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="toggleTodo(false)">Desmarcar todo</button>
        <button type="button" class="btn btn-sm btn-outline-primary" onclick="toggleTodo(true)">Marcar todo</button>
        <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-save me-1"></i>Guardar permisos</button>
    </div>
</div>
<div class="table-responsive">
<table class="table mb-0 align-middle" style="min-width:600px">
<thead class="table-dark">
<tr>
    <th style="width:35%">Módulo</th>
    @foreach($acciones as $acc)
    <th class="text-center" style="width:13%">{{ ucfirst($acc) }}</th>
    @endforeach
    <th class="text-center" style="width:13%">Sección</th>
</tr>
</thead>
<tbody>
@foreach($matriz as $seccion => $modulos)
<tr class="table-light">
    <td colspan="{{ count($acciones) + 2 }}" class="fw-semibold text-uppercase small text-muted py-1 ps-3">
        <i class="bi bi-folder2 me-1"></i>{{ $seccion }}
        <button type="button" class="btn btn-xs btn-link text-muted p-0 ms-2 small" onclick="toggleSeccion('{{ $seccion }}', true)">Todo</button>
        <button type="button" class="btn btn-xs btn-link text-muted p-0 ms-1 small" onclick="toggleSeccion('{{ $seccion }}', false)">Ninguno</button>
    </td>
</tr>
@foreach($modulos as $modKey => $modNombre)
<tr>
    <td class="ps-4">{{ $modNombre }}</td>
    @foreach($acciones as $acc)
    @php $perm = "{$modKey}.{$acc}"; @endphp
    <td class="text-center">
        @if(\Spatie\Permission\Models\Permission::where('name',$perm)->exists())
        <input type="checkbox" name="permisos[]" value="{{ $perm }}"
            class="form-check-input seccion-{{ $seccion }}"
            {{ in_array($perm, $permisosGrupo) ? 'checked' : '' }}>
        @else
        <span class="text-muted small">—</span>
        @endif
    </td>
    @endforeach
    <td></td>
</tr>
@endforeach
@endforeach

{{-- Permisos especiales --}}
<tr class="table-light">
    <td colspan="{{ count($acciones) + 2 }}" class="fw-semibold text-uppercase small text-muted py-1 ps-3">
        <i class="bi bi-star me-1"></i>Permisos Especiales
    </td>
</tr>
@foreach($especiales as $perm)
<tr>
    <td class="ps-4"><code class="small">{{ $perm }}</code></td>
    <td colspan="{{ count($acciones) }}" class="text-center">
        <input type="checkbox" name="permisos[]" value="{{ $perm }}"
            class="form-check-input"
            {{ in_array($perm, $permisosGrupo) ? 'checked' : '' }}>
        <small class="text-muted ms-1">Activar</small>
    </td>
    <td></td>
</tr>
@endforeach

@if($permisosDesconocidos->isNotEmpty())
<tr class="table-light">
    <td colspan="{{ count($acciones) + 2 }}" class="fw-semibold text-uppercase small text-warning py-1 ps-3">
        <i class="bi bi-plus-circle me-1"></i>Módulos Detectados Automáticamente
    </td>
</tr>
@foreach($permisosDesconocidos as $perm)
<tr>
    <td class="ps-4"><code class="small text-warning">{{ $perm->name }}</code></td>
    <td colspan="{{ count($acciones) }}" class="text-center">
        <input type="checkbox" name="permisos[]" value="{{ $perm->name }}"
            class="form-check-input"
            {{ in_array($perm->name, $permisosGrupo) ? 'checked' : '' }}>
        <small class="text-muted ms-1">Activar</small>
    </td>
    <td></td>
</tr>
@endforeach
@endif

</tbody>
</table>
</div>
<div class="card-footer text-end">
    <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i>Guardar todos los permisos</button>
</div>
</div>
</form>

@push('scripts')
<script>
function toggleTodo(marcar) {
    document.querySelectorAll('#formPermisos input[type=checkbox]').forEach(c => c.checked = marcar);
}
function toggleSeccion(sec, marcar) {
    document.querySelectorAll('.seccion-' + sec).forEach(c => c.checked = marcar);
}
</script>
@endpush
@endsection
