@extends('layouts.app')
@section('titulo', $facturaProveedor->numero_referencia)
@section('contenido')

<div class="row g-3">
<div class="col-md-4">
    <div class="card mb-3">
        <div class="card-header fw-semibold">Datos de la Factura</div>
        <div class="list-group list-group-flush">
            <div class="list-group-item"><small class="text-muted d-block">N° Interno</small><strong>{{ $facturaProveedor->numero_referencia }}</strong></div>
            <div class="list-group-item"><small class="text-muted d-block">N° Factura Proveedor</small>{{ $facturaProveedor->numero_factura_proveedor }}</div>
            @if($facturaProveedor->timbrado_proveedor)
            <div class="list-group-item"><small class="text-muted d-block">Timbrado</small>{{ $facturaProveedor->timbrado_proveedor }}</div>
            @endif
            <div class="list-group-item"><small class="text-muted d-block">Proveedor</small>{{ $facturaProveedor->proveedor->nombre ?? '—' }}</div>
            @if($facturaProveedor->centroCosto)
            <div class="list-group-item"><small class="text-muted d-block">Centro de Costo</small>{{ $facturaProveedor->centroCosto->codigo }} — {{ $facturaProveedor->centroCosto->nombre }}</div>
            @endif
            <div class="list-group-item"><small class="text-muted d-block">Fecha Emisión</small>{{ $facturaProveedor->fecha_emision->format('d/m/Y') }}</div>
            <div class="list-group-item"><small class="text-muted d-block">Fecha Vencimiento</small>{{ $facturaProveedor->fecha_vencimiento?->format('d/m/Y') ?? '—' }}</div>
            <div class="list-group-item"><small class="text-muted d-block">Estado</small>
                <x-badge-estado grupo="facturas_proveedor.estado" :valor="$facturaProveedor->estado" />
            </div>
            <div class="list-group-item"><small class="text-muted d-block">Subtotal</small>{{ number_format($facturaProveedor->subtotal,0,',','.') }}</div>
            <div class="list-group-item"><small class="text-muted d-block">IVA</small>{{ number_format($facturaProveedor->iva_total,0,',','.') }}</div>
            @if($facturaProveedor->retiene_iva)
            <div class="list-group-item"><small class="text-muted d-block">Retención IVA</small>
                -{{ number_format($facturaProveedor->retencion_monto,0,',','.') }}
                @if($facturaProveedor->retencion_numero)<br><small class="text-muted">Comprobante {{ $facturaProveedor->retencion_numero }} @if($facturaProveedor->retencion_timbrado) (Timbrado {{ $facturaProveedor->retencion_timbrado }}) @endif</small>@endif
            </div>
            @endif
            <div class="list-group-item"><small class="text-muted d-block">Total</small><strong class="fs-5">{{ number_format($facturaProveedor->total,0,',','.') }}</strong></div>
            <div class="list-group-item"><small class="text-muted d-block">Saldo Pendiente</small>
                <strong class="{{ $facturaProveedor->saldo_pendiente > 0 ? 'text-danger' : 'text-success' }}">{{ number_format($facturaProveedor->saldo_pendiente,0,',','.') }}</strong>
            </div>
            @if($facturaProveedor->observaciones)
            <div class="list-group-item"><small class="text-muted d-block">Observaciones</small>{{ $facturaProveedor->observaciones }}</div>
            @endif
        </div>
    </div>

    <div class="d-grid gap-2">
        @can('facturas_proveedor.editar')
        @if($facturaProveedor->estado === 'pendiente' && (float)$facturaProveedor->monto_pagado <= 0)
        <a href="{{ route('facturas-proveedor.edit',$facturaProveedor) }}" class="btn btn-outline-warning"><i class="bi bi-pencil me-1"></i>Editar</a>
        @endif
        @endcan
        @can('facturas_proveedor.eliminar')
        @if((float)$facturaProveedor->monto_pagado <= 0)
        <form method="POST" action="{{ route('facturas-proveedor.destroy',$facturaProveedor) }}" onsubmit="return confirm('¿Eliminar esta factura de proveedor?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-outline-danger w-100"><i class="bi bi-trash me-1"></i>Eliminar</button>
        </form>
        @endif
        @endcan
        <a href="{{ route('facturas-proveedor.index') }}" class="btn btn-outline-secondary">Volver a lista</a>
    </div>
</div>

<div class="col-md-8">
    <div class="card mb-3">
        <div class="card-header fw-semibold">Conceptos</div>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead><tr><th>Concepto</th><th>Centro de Costo</th><th class="text-end">Cant.</th><th class="text-end">P. Unit.</th><th class="text-end">Subtotal</th></tr></thead>
                <tbody>
                @foreach($facturaProveedor->detalles as $d)
                <tr>
                    <td>{{ $d->concepto }}</td>
                    <td class="text-muted small">{{ $d->centroCosto ? $d->centroCosto->codigo.' — '.$d->centroCosto->nombre : '—' }}</td>
                    <td class="text-end">{{ number_format($d->cantidad,2) }}</td>
                    <td class="text-end">{{ number_format($d->precio_unitario,0,',','.') }}</td>
                    <td class="text-end">{{ number_format($d->subtotal,0,',','.') }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header fw-semibold">Cuotas</div>
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead><tr><th>N°</th><th>Vencimiento</th><th class="text-end">Monto</th><th class="text-center">Estado</th><th></th></tr></thead>
                <tbody>
                @foreach($facturaProveedor->cuotas as $c)
                <tr>
                    <td>{{ $c->numero_cuota }}</td>
                    <td>{{ $c->fecha_vencimiento->format('d/m/Y') }}</td>
                    <td class="text-end">{{ number_format($c->monto,0,',','.') }}</td>
                    <td class="text-center">
                        @if($c->pagada)
                        <span class="badge bg-success">Pagada {{ $c->fecha_pago?->format('d/m/Y') }}</span>
                        @else
                        <span class="badge bg-warning text-dark">Pendiente</span>
                        @endif
                    </td>
                    <td>
                        @can('facturas_proveedor.editar')
                        @if(!$c->pagada)
                        <form method="POST" action="{{ route('facturas-proveedor.cuotas.pagar',$c) }}" onsubmit="return confirm('¿Marcar esta cuota como pagada?')">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-success"><i class="bi bi-check-lg"></i> Marcar pagada</button>
                        </form>
                        @endif
                        @endcan
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
@endsection
