@extends('layouts.app')
@section('titulo', $notaRemision->numero_completo)
@section('contenido')

@if($notaRemision->modo === 'local')
<div class="alert alert-warning"><i class="bi bi-exclamation-triangle me-2"></i>Documento en modo demo, sin validez tributaria.</div>
@endif

<div class="row g-3">
<div class="col-md-4">
    <div class="card mb-3">
        <div class="card-header fw-semibold">Datos de la Remisión</div>
        <div class="list-group list-group-flush">
            <div class="list-group-item"><small class="text-muted d-block">N° Documento</small><strong>{{ $notaRemision->numero_completo }}</strong></div>
            <div class="list-group-item"><small class="text-muted d-block">Pedido</small>{{ $notaRemision->pedido->numero_referencia ?? '—' }}</div>
            <div class="list-group-item"><small class="text-muted d-block">Cliente</small>{{ $notaRemision->pedido->cliente->nombre ?? '—' }}</div>
            <div class="list-group-item"><small class="text-muted d-block">Fecha</small>{{ $notaRemision->fecha_emision->format('d/m/Y') }}</div>
            <div class="list-group-item"><small class="text-muted d-block">Motivo</small>{{ ucfirst($notaRemision->motivo) }}</div>
            <div class="list-group-item"><small class="text-muted d-block">Afecta stock</small>
                @if($notaRemision->afecta_stock)
                <span class="badge bg-success">Sí</span>
                @else
                <span class="badge bg-secondary">No</span>
                @endif
            </div>
            <div class="list-group-item"><small class="text-muted d-block">Destino</small>{{ $notaRemision->direccion_destino ?: '—' }}</div>
            <div class="list-group-item"><small class="text-muted d-block">Transportista</small>{{ $notaRemision->transportista ?: '—' }} {{ $notaRemision->vehiculo_placa ? '('.$notaRemision->vehiculo_placa.')' : '' }}</div>
        </div>
    </div>
    <div class="d-grid gap-2">
        <a href="{{ route('notas-remision.pdf',$notaRemision) }}" target="_blank" class="btn btn-outline-secondary"><i class="bi bi-file-pdf me-1"></i>Ver / Descargar PDF</a>
        <a href="{{ route('notas-remision.index') }}" class="btn btn-outline-secondary">Volver a lista</a>
    </div>
</div>
<div class="col-md-8">
    <div class="card">
        <div class="card-header fw-semibold">Productos Remitidos</div>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead><tr><th>Producto</th><th class="text-end">Cantidad</th></tr></thead>
                <tbody>
                @foreach($notaRemision->detalles as $d)
                <tr><td>{{ $d->producto->nombre ?? '—' }}</td><td class="text-end">{{ number_format($d->cantidad,2) }}</td></tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
@endsection
