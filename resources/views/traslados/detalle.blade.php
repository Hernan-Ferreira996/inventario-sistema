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
            <div class="list-group-item"><small class="text-muted d-block">Registrado por</small>{{ $traslado->usuario->name ?? '—' }}</div>
        </div>
    </div>
    <a href="{{ route('traslados.index') }}" class="btn btn-outline-secondary w-100">Volver a lista</a>
</div>
<div class="col-md-8">
    <div class="card">
        <div class="card-header fw-semibold">Productos Trasladados</div>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead><tr><th>Producto</th><th class="text-end">Cantidad</th></tr></thead>
                <tbody>
                @foreach($traslado->detalles as $d)
                <tr><td>{{ $d->producto->nombre ?? '—' }}</td><td class="text-end">{{ number_format($d->cantidad,2) }}</td></tr>
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
