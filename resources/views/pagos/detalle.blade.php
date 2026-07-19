@extends('layouts.app')
@section('titulo','Detalle de Pago')
@section('contenido')
<div class="row justify-content-center"><div class="col-md-6">
<div class="card">
    <div class="card-header fw-semibold">Pago — {{ $pago->factura->numero_documento ?? '—' }}</div>
    <div class="list-group list-group-flush">
        <div class="list-group-item"><small class="text-muted d-block">N° Recibo</small>{{ $pago->numero_recibo ?? '—' }}</div>
        <div class="list-group-item"><small class="text-muted d-block">Factura</small>{{ $pago->factura->numero_documento ?? '—' }}</div>
        <div class="list-group-item"><small class="text-muted d-block">Cliente</small>{{ $pago->factura->pedido->cliente->nombre ?? '—' }}</div>
        <div class="list-group-item"><small class="text-muted d-block">Fecha</small>{{ $pago->fecha_pago->format('d/m/Y') }}</div>
        <div class="list-group-item"><small class="text-muted d-block">Método</small>{{ $pago->metodoPago->nombre ?? '—' }}</div>
        @if($pago->caja)<div class="list-group-item"><small class="text-muted d-block">Caja</small>{{ $pago->caja->nombre }}</div>@endif
        @if($pago->cobrador)<div class="list-group-item"><small class="text-muted d-block">Cobrador</small>{{ $pago->cobrador->name }}</div>@endif
        <div class="list-group-item"><small class="text-muted d-block">Rendición</small>
            @if($pago->rendicion_id)
            <span class="badge bg-success">Rendido</span>
            @else
            <span class="badge bg-warning text-dark">Pendiente de rendir</span>
            @endif
        </div>
        <div class="list-group-item"><small class="text-muted d-block">Monto</small><strong class="fs-5 text-success">{{ number_format($pago->monto,0,',','.') }}</strong></div>
        <div class="list-group-item"><small class="text-muted d-block">Referencia</small>{{ $pago->referencia ?: '—' }}</div>
        @if($pago->notas)<div class="list-group-item"><small class="text-muted d-block">Notas</small>{{ $pago->notas }}</div>@endif
    </div>
    <div class="card-body">
        <div class="d-grid gap-2 mb-3">
            <a href="{{ route('facturas.show',$pago->factura) }}" class="btn btn-outline-primary">Ver Factura</a>
            <a href="{{ route('pagos.pdf',$pago) }}" target="_blank" class="btn btn-outline-secondary"><i class="bi bi-file-pdf me-1"></i>Ver / Descargar Recibo</a>
            <a href="{{ route('pagos.index') }}" class="btn btn-outline-secondary">Volver a lista</a>
        </div>
        @can('pagos.eliminar')
        @if(!$pago->rendicion_id)
        <form method="POST" action="{{ route('pagos.destroy',$pago) }}" class="border-top pt-3" onsubmit="return confirm('¿Anular este pago?')">
            @csrf @method('DELETE')
            @if($requiereCodigoSupervisor)
            <div class="mb-2">
                <label class="form-label fw-semibold small">Código de supervisor</label>
                <input type="password" name="codigo_supervisor" class="form-control form-control-sm" required>
            </div>
            @endif
            <button type="submit" class="btn btn-outline-danger btn-sm w-100"><i class="bi bi-trash me-1"></i>Anular Pago</button>
        </form>
        @endif
        @endcan
    </div>
</div>
</div></div>
@endsection
