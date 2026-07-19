@extends('layouts.app')
@section('titulo', $presupuesto->numero_documento)
@section('contenido')

<div class="row g-3">
<div class="col-md-4">
    <div class="card mb-3">
        <div class="card-header fw-semibold">Datos del Presupuesto</div>
        <div class="list-group list-group-flush">
            <div class="list-group-item"><small class="text-muted d-block">N° Documento</small><strong>{{ $presupuesto->numero_documento }}</strong></div>
            <div class="list-group-item"><small class="text-muted d-block">Cliente</small>{{ $presupuesto->cliente->nombre ?? '—' }}</div>
            <div class="list-group-item"><small class="text-muted d-block">Fecha Emisión</small>{{ $presupuesto->fecha_emision->format('d/m/Y') }}</div>
            <div class="list-group-item"><small class="text-muted d-block">Válido Hasta</small>{{ $presupuesto->fecha_validez?->format('d/m/Y') ?? 'Sin límite' }}</div>
            <div class="list-group-item"><small class="text-muted d-block">Estado</small>
                <x-badge-estado grupo="presupuestos.estado" :valor="$presupuesto->estado" />
            </div>
            <div class="list-group-item"><small class="text-muted d-block">Etapa del Pipeline</small>
                <x-badge-estado grupo="presupuestos.etapa" :valor="$presupuesto->etapa" />
            </div>
            <div class="list-group-item"><small class="text-muted d-block">Total</small><strong class="fs-5">{{ number_format($presupuesto->total,0,',','.') }}</strong></div>
        </div>
    </div>

    <div class="d-grid gap-2">
        @can('presupuestos.editar')
        @if($presupuesto->estado === 'pendiente')
        <form method="POST" action="{{ route('presupuestos.update',$presupuesto) }}" class="d-grid gap-2">
            @csrf @method('PATCH')
            <input type="hidden" name="cliente_id" value="{{ $presupuesto->cliente_id }}">
            <input type="hidden" name="fecha_emision" value="{{ $presupuesto->fecha_emision->format('Y-m-d') }}">
            <input type="hidden" name="fecha_validez" value="{{ $presupuesto->fecha_validez?->format('Y-m-d') }}">
            @foreach($presupuesto->detalles as $i => $d)
            <input type="hidden" name="productos[{{ $i }}][producto_id]" value="{{ $d->producto_id }}">
            <input type="hidden" name="productos[{{ $i }}][cantidad]" value="{{ $d->cantidad }}">
            <input type="hidden" name="productos[{{ $i }}][precio_unitario]" value="{{ $d->precio_unitario }}">
            <input type="hidden" name="productos[{{ $i }}][descuento]" value="{{ $d->descuento }}">
            @endforeach
            <input type="hidden" name="estado" value="aprobado">
            <input type="hidden" name="etapa" value="{{ $presupuesto->etapa }}">
            <button type="submit" class="btn btn-success"><i class="bi bi-check-lg me-1"></i>Aprobar Presupuesto</button>
        </form>
        <a href="{{ route('presupuestos.edit',$presupuesto) }}" class="btn btn-outline-warning"><i class="bi bi-pencil me-1"></i>Editar</a>
        @endif
        @endcan

        @can('presupuestos.crear')
        @if($presupuesto->estado === 'aprobado')
        <form method="POST" action="{{ route('presupuestos.convertir',$presupuesto) }}">
            @csrf
            <button type="submit" class="btn btn-primary w-100"><i class="bi bi-arrow-right-circle me-1"></i>Convertir a Pedido de Venta</button>
        </form>
        @endif
        @if($presupuesto->estado === 'convertido' && $presupuesto->pedido)
        <a href="{{ route('pedidos.show',$presupuesto->pedido) }}" class="btn btn-outline-primary"><i class="bi bi-cart3 me-1"></i>Ver Pedido Generado</a>
        @endif
        @endcan

        @can('envios.crear')
        @if($presupuesto->estado === 'aprobado' && !Auth::user()?->esSuperAdmin())
        <a href="{{ route('notas-remision.create',['presupuesto' => $presupuesto->id]) }}" class="btn btn-outline-success"><i class="bi bi-truck me-1"></i>Generar Remisión (Anticipo)</a>
        @endif
        @endcan

        <a href="{{ route('presupuestos.pdf',$presupuesto) }}" target="_blank" class="btn btn-outline-secondary"><i class="bi bi-file-pdf me-1"></i>Ver / Descargar PDF</a>
        <a href="{{ route('presupuestos.index') }}" class="btn btn-outline-secondary">Volver a lista</a>
    </div>
</div>

<div class="col-md-8">
    <div class="card">
        <div class="card-header fw-semibold">Productos Presupuestados</div>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead><tr><th>Producto</th><th class="text-end">Cant.</th><th class="text-end">P. Unit.</th><th class="text-end">Desc%</th><th class="text-end">Subtotal</th></tr></thead>
                <tbody>
                @foreach($presupuesto->detalles as $d)
                <tr>
                    <td>{{ $d->producto->nombre ?? '—' }}</td>
                    <td class="text-end">{{ number_format($d->cantidad,2) }}</td>
                    <td class="text-end">{{ number_format($d->precio_unitario,0,',','.') }}</td>
                    <td class="text-end">{{ $d->descuento }}%</td>
                    <td class="text-end fw-semibold">{{ number_format($d->subtotal,0,',','.') }}</td>
                </tr>
                @endforeach
                </tbody>
                <tfoot><tr class="fw-bold bg-light"><td colspan="4" class="text-end">TOTAL:</td><td class="text-end">{{ number_format($presupuesto->total,0,',','.') }}</td></tr></tfoot>
            </table>
        </div>
    </div>
    @if($presupuesto->comentarios)
    <div class="card mt-3"><div class="card-body"><small class="text-muted d-block mb-1">Comentarios:</small>{{ $presupuesto->comentarios }}</div></div>
    @endif
</div>
</div>
@endsection
