@extends('layouts.app')
@section('titulo','Cierre de Períodos')
@section('contenido')
<div class="row g-3">
<div class="col-md-5">
    <div class="card mb-3">
        <div class="card-header fw-semibold">Estado actual</div>
        <div class="card-body">
            @if($fechaVigente)
            <p class="mb-1 text-muted small">Último cierre vigente:</p>
            <p class="fs-4 fw-bold mb-0">{{ $fechaVigente->format('d/m/Y') }}</p>
            <p class="text-muted small mt-2 mb-0">Los documentos con fecha hasta este día no se pueden eliminar ni anular.</p>
            @else
            <p class="text-muted mb-0">No hay ningún cierre registrado todavía. Todos los documentos pueden editarse o eliminarse libremente.</p>
            @endif
        </div>
    </div>

    @can('contabilidad.crear')
    <div class="card">
        <div class="card-header fw-semibold">Cerrar un nuevo período</div>
        <div class="card-body">
            <form method="POST" action="{{ route('cierres.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">Fecha de cierre *</label>
                    <input type="date" name="fecha_cierre" class="form-control @error('fecha_cierre') is-invalid @enderror" value="{{ old('fecha_cierre') }}" max="{{ date('Y-m-d') }}" required>
                    @error('fecha_cierre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <small class="text-muted">Todos los documentos con fecha hasta este día quedarán bloqueados para eliminar/anular.</small>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Observaciones</label>
                    <textarea name="observaciones" class="form-control" rows="2">{{ old('observaciones') }}</textarea>
                </div>
                <button type="submit" class="btn btn-danger" onclick="return confirm('¿Confirmás el cierre? Esta acción no se puede deshacer.')">
                    <i class="bi bi-lock-fill me-1"></i>Cerrar período
                </button>
            </form>
        </div>
    </div>
    @endcan
</div>

<div class="col-md-7">
    <div class="card">
        <div class="card-header fw-semibold">Historial de cierres</div>
        <div class="table-responsive">
            <table class="table table-sm mb-0 align-middle">
                <thead class="table-light"><tr><th>Fecha de Cierre</th><th>Realizado por</th><th>Fecha</th><th>Observaciones</th></tr></thead>
                <tbody>
                @forelse($cierres as $c)
                <tr>
                    <td class="fw-semibold">{{ $c->fecha_cierre->format('d/m/Y') }}</td>
                    <td>{{ $c->usuario->name ?? '—' }}</td>
                    <td class="text-muted small">{{ $c->created_at->format('d/m/Y H:i') }}</td>
                    <td class="text-muted small">{{ $c->observaciones ?? '—' }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center py-4 text-muted">Sin cierres registrados.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if($cierres->hasPages())<div class="card-footer py-2">{{ $cierres->links() }}</div>@endif
    </div>
</div>
</div>
@endsection
