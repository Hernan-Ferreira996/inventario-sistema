@extends('layouts.app')
@section('titulo', $notaCredito->numero_completo)
@section('contenido')

@if($notaCredito->modo === 'local')
<div class="alert alert-warning"><i class="bi bi-exclamation-triangle me-2"></i>Documento en modo demo, sin validez tributaria.</div>
@endif

<div class="row g-3">
<div class="col-md-4">
    <div class="card mb-3">
        <div class="card-header fw-semibold">Datos de la Nota de Crédito</div>
        <div class="list-group list-group-flush">
            <div class="list-group-item"><small class="text-muted d-block">N° Documento</small><strong>{{ $notaCredito->numero_completo }}</strong></div>
            <div class="list-group-item"><small class="text-muted d-block">Factura Asociada</small>{{ $notaCredito->factura->numero_documento ?? '—' }}</div>
            <div class="list-group-item"><small class="text-muted d-block">Cliente</small>{{ $notaCredito->factura->pedido->cliente->nombre ?? '—' }}</div>
            <div class="list-group-item"><small class="text-muted d-block">Fecha</small>{{ $notaCredito->fecha_emision->format('d/m/Y') }}</div>
            <div class="list-group-item"><small class="text-muted d-block">Motivo</small>{{ ucfirst(str_replace('_',' ',$notaCredito->motivo)) }}</div>
            <div class="list-group-item"><small class="text-muted d-block">Generado por</small>{{ $notaCredito->usuario->name ?? '—' }}</div>
            <div class="list-group-item"><small class="text-muted d-block">Total</small><strong class="fs-5">{{ number_format($notaCredito->total,0,',','.') }}</strong></div>
        </div>
    </div>
    <div class="d-grid gap-2">
        <a href="{{ route('notas-credito.pdf',$notaCredito) }}" target="_blank" class="btn btn-outline-secondary"><i class="bi bi-file-pdf me-1"></i>Ver / Descargar PDF</a>
        <a href="{{ route('notas-credito.index') }}" class="btn btn-outline-secondary">Volver a lista</a>
    </div>
</div>
<div class="col-md-8">
    <div class="card">
        <div class="card-header fw-semibold">Productos Acreditados</div>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead><tr><th>Producto</th><th class="text-end">Cant.</th><th class="text-end">P. Unit.</th><th class="text-end">Subtotal</th></tr></thead>
                <tbody>
                @foreach($notaCredito->detalles as $d)
                <tr>
                    <td>{{ $d->producto->nombre ?? '—' }}</td>
                    <td class="text-end">{{ number_format($d->cantidad,2) }}</td>
                    <td class="text-end">{{ number_format($d->precio_unitario,0,',','.') }}</td>
                    <td class="text-end">{{ number_format($d->subtotal,0,',','.') }}</td>
                </tr>
                @endforeach
                </tbody>
                <tfoot><tr class="fw-bold bg-light"><td colspan="3" class="text-end">TOTAL:</td><td class="text-end">{{ number_format($notaCredito->total,0,',','.') }}</td></tr></tfoot>
            </table>
        </div>
    </div>
</div>
</div>
@endsection
