@extends('layouts.app')
@section('titulo','Confirmar Recepción — Traslado #' . $traslado->id)
@section('contenido')
<div class="card">
    <div class="card-header fw-semibold">Confirmar Recepción — Traslado #{{ $traslado->id }}</div>
    <div class="card-body">
        <div class="row g-2 mb-3">
            <div class="col-md-6"><span class="text-muted small">Origen:</span> <span class="badge bg-secondary">{{ $traslado->ubicacionOrigen->nombre ?? '—' }}</span></div>
            <div class="col-md-6"><span class="text-muted small">Destino:</span> <span class="badge bg-primary">{{ $traslado->ubicacionDestino->nombre ?? '—' }}</span></div>
        </div>

        <form method="POST" action="{{ route('traslados.recibir',$traslado) }}">
            @csrf
            <div class="table-responsive mb-3">
                <table class="table table-sm align-middle">
                    <thead><tr><th>Producto</th><th class="text-end">Cant. Enviada</th><th style="width:180px">Cant. Recibida *</th></tr></thead>
                    <tbody>
                    @foreach($traslado->detalles as $d)
                    <tr>
                        <td>{{ $d->producto->nombre ?? '—' }}</td>
                        <td class="text-end">{{ number_format($d->cantidad,2) }}</td>
                        <td>
                            <input type="hidden" name="detalles[{{ $loop->index }}][id]" value="{{ $d->id }}">
                            <input type="number" name="detalles[{{ $loop->index }}][cantidad_recibida]" class="form-control form-control-sm" step="0.01" min="0" value="{{ $d->cantidad }}" required>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <p class="text-muted small">Si la cantidad recibida difiere de la enviada (faltante o excedente), la diferencia queda registrada en el detalle del traslado.</p>
            <div class="d-flex gap-2 border-top pt-3">
                <button type="submit" class="btn btn-success px-4" onclick="return confirm('¿Confirmar la recepción de este traslado? Esta acción no se puede deshacer.')">
                    <i class="bi bi-check-circle me-1"></i>Confirmar Recepción
                </button>
                <a href="{{ route('traslados.show',$traslado) }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
