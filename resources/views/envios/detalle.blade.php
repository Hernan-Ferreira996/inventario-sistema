@extends('layouts.app')
@section('titulo', $envio->numero_envio)
@section('contenido')
<div class="row g-3">
<div class="col-md-4">
    <div class="card mb-3">
        <div class="card-header fw-semibold">Datos del Envío</div>
        <div class="list-group list-group-flush">
            <div class="list-group-item"><small class="text-muted d-block">N° Envío</small><strong>{{ $envio->numero_envio }}</strong></div>
            <div class="list-group-item"><small class="text-muted d-block">Pedido</small>{{ $envio->pedido->numero_referencia ?? '—' }}</div>
            <div class="list-group-item"><small class="text-muted d-block">Cliente</small>{{ $envio->pedido->cliente->nombre ?? '—' }}</div>
            <div class="list-group-item"><small class="text-muted d-block">F. Empaque</small>{{ $envio->fecha_empaque->format('d/m/Y') }}</div>
            <div class="list-group-item"><small class="text-muted d-block">F. Entrega</small>{{ $envio->fecha_entrega?->format('d/m/Y') ?? '—' }}</div>
            <div class="list-group-item"><small class="text-muted d-block">Estado</small>
                @php $colores=['preparando'=>'bg-warning text-dark','enviado'=>'bg-info text-dark','entregado'=>'bg-success','devuelto'=>'bg-danger']; @endphp
                <span class="badge {{ $colores[$envio->estado] ?? 'bg-secondary' }}">{{ ucfirst($envio->estado) }}</span>
            </div>
            @if($envio->transportista)<div class="list-group-item"><small class="text-muted d-block">Transportista</small>{{ $envio->transportista }}</div>@endif
            @if($envio->chofer)<div class="list-group-item"><small class="text-muted d-block">Chofer</small>{{ $envio->chofer }}</div>@endif
            @if($envio->vehiculo_placa)<div class="list-group-item"><small class="text-muted d-block">Vehículo</small>{{ $envio->vehiculo_placa }}</div>@endif
        </div>
    </div>
    <div class="d-grid gap-2">
        @can('envios.editar')
        <a href="{{ route('envios.edit',$envio) }}" class="btn btn-outline-warning"><i class="bi bi-pencil me-1"></i>Actualizar Estado</a>
        @endcan
        <a href="{{ route('envios.index') }}" class="btn btn-outline-secondary">Volver a lista</a>
    </div>
</div>
<div class="col-md-8">
    <div class="card">
        <div class="card-header fw-semibold">Productos Enviados</div>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead><tr><th>Producto</th><th class="text-end">Cantidad</th></tr></thead>
                <tbody>
                @foreach($envio->detalles as $d)
                <tr><td>{{ $d->producto->nombre ?? '—' }}</td><td class="text-end">{{ number_format($d->cantidad,2) }}</td></tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @if($envio->comentarios)
    <div class="card mt-3"><div class="card-body"><small class="text-muted d-block mb-1">Comentarios:</small>{{ $envio->comentarios }}</div></div>
    @endif
</div>
</div>
@endsection
