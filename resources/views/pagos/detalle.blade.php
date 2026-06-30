@extends('layouts.app')
@section('titulo','Detalle de Pago')
@section('contenido')
<div class="row justify-content-center"><div class="col-md-6">
<div class="card">
    <div class="card-header fw-semibold">Pago — {{ $pago->factura->numero_documento ?? '—' }}</div>
    <div class="list-group list-group-flush">
        <div class="list-group-item"><small class="text-muted d-block">Factura</small>{{ $pago->factura->numero_documento ?? '—' }}</div>
        <div class="list-group-item"><small class="text-muted d-block">Cliente</small>{{ $pago->factura->pedido->cliente->nombre ?? '—' }}</div>
        <div class="list-group-item"><small class="text-muted d-block">Fecha</small>{{ $pago->fecha_pago->format('d/m/Y') }}</div>
        <div class="list-group-item"><small class="text-muted d-block">Método</small>{{ $pago->metodoPago->nombre ?? '—' }}</div>
        <div class="list-group-item"><small class="text-muted d-block">Monto</small><strong class="fs-5 text-success">{{ number_format($pago->monto,0,',','.') }}</strong></div>
        <div class="list-group-item"><small class="text-muted d-block">Referencia</small>{{ $pago->referencia ?: '—' }}</div>
        @if($pago->notas)<div class="list-group-item"><small class="text-muted d-block">Notas</small>{{ $pago->notas }}</div>@endif
    </div>
    <div class="card-body d-grid gap-2">
        <a href="{{ route('facturas.show',$pago->factura) }}" class="btn btn-outline-primary">Ver Factura</a>
        <a href="{{ route('pagos.index') }}" class="btn btn-outline-secondary">Volver a lista</a>
    </div>
</div>
</div></div>
@endsection
