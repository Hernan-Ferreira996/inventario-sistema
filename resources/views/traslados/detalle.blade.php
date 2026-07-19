@extends('layouts.app')
@section('titulo','Traslado #' . $traslado->id)
@section('contenido')
<div class="row g-3">
<div class="col-md-4">
    <div class="card mb-3">
        <div class="card-header fw-semibold">Datos del Traslado</div>
        <div class="list-group list-group-flush">
            <div class="list-group-item"><small class="text-muted d-block">Fecha</small>{{ $traslado->fecha_traslado->format('d/m/Y') }}</div>
            <div class="list-group-item"><small class="text-muted d-block">Origen</small><span class="badge bg-secondary">{{ $traslado->ubicacionOrigen->nombre ?? '—' }}</span></div>
            <div class="list-group-item"><small class="text-muted d-block">Destino</small><span class="badge bg-primary">{{ $traslado->ubicacionDestino->nombre ?? '—' }}</span></div>
            <div class="list-group-item"><small class="text-muted d-block">Referencia</small>{{ $traslado->referencia ?: '—' }}</div>
            <div class="list-group-item"><small class="text-muted d-block">Estado</small>
                <x-badge-estado grupo="traslados.estado" :valor="$traslado->estado" />
            </div>
            <div class="list-group-item"><small class="text-muted d-block">Registrado por</small>{{ $traslado->usuario->name ?? '—' }}</div>
            @if($traslado->estado === 'recibido')
            <div class="list-group-item"><small class="text-muted d-block">Recepción confirmada</small>{{ $traslado->fecha_recepcion?->format('d/m/Y') }} por {{ $traslado->usuarioRecepcion->name ?? '—' }}</div>
            @endif
        </div>
    </div>
    <div class="d-grid gap-2">
        @if($traslado->estado === 'en_transito')
        @can('productos.editar')
        @if(!Auth::user()?->esSuperAdmin())
        <a href="{{ route('traslados.confirmar-recepcion',$traslado) }}" class="btn btn-success"><i class="bi bi-box-arrow-in-down me-1"></i>Confirmar Recepción</a>
        @endif
        @endcan
        @endif
        <a href="{{ route('traslados.index') }}" class="btn btn-outline-secondary">Volver a lista</a>
    </div>
</div>
<div class="col-md-8">
    <div class="card">
        <div class="card-header fw-semibold">Productos Trasladados</div>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead><tr><th>Producto</th><th class="text-end">Cant. Enviada</th><th class="text-end">Cant. Recibida</th><th class="text-end">Diferencia</th></tr></thead>
                <tbody>
                @foreach($traslado->detalles as $d)
                <tr>
                    <td>{{ $d->producto->nombre ?? '—' }}</td>
                    <td class="text-end">{{ number_format($d->cantidad,2) }}</td>
                    <td class="text-end">{{ $d->cantidad_recibida !== null ? number_format($d->cantidad_recibida,2) : '—' }}</td>
                    <td class="text-end">
                        @if($d->cantidad_recibida !== null)
                            @php $diferencia = $d->cantidad_recibida - $d->cantidad; @endphp
                            <span class="{{ $diferencia < 0 ? 'text-danger' : ($diferencia > 0 ? 'text-success' : 'text-muted') }}">
                                {{ $diferencia > 0 ? '+' : '' }}{{ number_format($diferencia,2) }}
                            </span>
                        @else
                            —
                        @endif
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @if($traslado->notas)
    <div class="card mt-3"><div class="card-body"><small class="text-muted d-block mb-1">Notas:</small>{{ $traslado->notas }}</div></div>
    @endif
</div>
</div>
@endsection
