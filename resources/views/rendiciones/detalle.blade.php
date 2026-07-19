@extends('layouts.app')
@section('titulo','Rendición')
@section('contenido')
<div class="row g-3">
<div class="col-md-4">
    <div class="card mb-3">
        <div class="card-header fw-semibold">Datos de la Rendición</div>
        <div class="list-group list-group-flush">
            <div class="list-group-item"><small class="text-muted d-block">Fecha</small>{{ $rendicion->fecha->format('d/m/Y') }}</div>
            <div class="list-group-item"><small class="text-muted d-block">Caja</small>{{ $rendicion->caja->nombre ?? '—' }}</div>
            <div class="list-group-item"><small class="text-muted d-block">Cobrador</small>{{ $rendicion->cobrador->name ?? '—' }}</div>
            <div class="list-group-item"><small class="text-muted d-block">Registrada por</small>{{ $rendicion->usuario->name ?? '—' }}</div>
            <div class="list-group-item"><small class="text-muted d-block">Monto Total</small><strong class="fs-5">{{ number_format($rendicion->monto_total,0,',','.') }}</strong></div>
            @if($rendicion->observaciones)
            <div class="list-group-item"><small class="text-muted d-block">Observaciones</small>{{ $rendicion->observaciones }}</div>
            @endif
        </div>
    </div>
    <a href="{{ route('rendiciones.index') }}" class="btn btn-outline-secondary w-100">Volver a lista</a>
</div>
<div class="col-md-8">
    <div class="card">
        <div class="card-header fw-semibold">Pagos Incluidos</div>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead><tr><th>Recibo</th><th>Factura</th><th>Cliente</th><th>Fecha</th><th class="text-end">Monto</th></tr></thead>
                <tbody>
                @foreach($rendicion->pagos as $p)
                <tr>
                    <td><a href="{{ route('pagos.show',$p) }}">{{ $p->numero_recibo }}</a></td>
                    <td>{{ $p->factura->numero_documento ?? '—' }}</td>
                    <td>{{ $p->factura->pedido->cliente->nombre ?? '—' }}</td>
                    <td>{{ $p->fecha_pago->format('d/m/Y') }}</td>
                    <td class="text-end">{{ number_format($p->monto,0,',','.') }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
@endsection
