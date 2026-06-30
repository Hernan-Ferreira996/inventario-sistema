@extends('layouts.app')
@section('titulo','Auditoria del Sistema')
@section('contenido')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Registro de Auditoria</h5>
    <span class="badge bg-secondary">{{ $registros->total() }} eventos</span>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light"><tr>
                <th>Fecha</th><th>Usuario</th><th>Accion</th><th>Modulo</th><th>Registro</th><th>Cambios</th>
            </tr></thead>
            <tbody>
            @forelse($registros as $r)
            <tr>
                <td><small>{{ $r->created_at->format('d/m/Y H:i:s') }}</small></td>
                <td>{{ $r->causer?->name ?? 'Sistema' }}</td>
                <td>
                    @php
                        $colores = ['created' => 'bg-success', 'updated' => 'bg-warning text-dark', 'deleted' => 'bg-danger'];
                        $etiquetas = ['created' => 'Creado', 'updated' => 'Editado', 'deleted' => 'Eliminado'];
                    @endphp
                    <span class="badge {{ $colores[$r->event] ?? 'bg-secondary' }}">{{ $etiquetas[$r->event] ?? $r->event }}</span>
                </td>
                <td><span class="badge bg-secondary">{{ $r->log_name }}</span></td>
                <td>{{ $r->subject?->nombre ?? $r->subject?->numero_referencia ?? ('#'.$r->subject_id) }}</td>
                <td>
                    @if($r->changes()->isNotEmpty())
                    <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#cambios-{{ $r->id }}">
                        Ver detalle
                    </button>
                    <div class="collapse mt-2" id="cambios-{{ $r->id }}">
                        <pre class="bg-light p-2 rounded small mb-0">{{ json_encode($r->changes(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                    </div>
                    @else
                    <span class="text-muted">—</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center py-5 text-muted">
                <i class="bi bi-clock-history d-block mb-2" style="font-size:2rem"></i>
                Sin actividad registrada todavia
            </td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($registros->hasPages())<div class="card-footer py-2">{{ $registros->links() }}</div>@endif
</div>
@endsection
