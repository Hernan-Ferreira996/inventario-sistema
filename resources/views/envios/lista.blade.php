@extends('layouts.app')
@section('titulo','Envíos')
@section('contenido')
<h5 class="mb-3">Envíos / Despachos</h5>
<div class="card mb-3"><div class="card-body py-2">
<form method="GET" class="row g-2">
    <div class="col-md-5"><input type="text" name="q" class="form-control" placeholder="N° envío, pedido o cliente..." value="{{ request('q') }}"></div>
    <div class="col-md-3">
        <select name="estado" class="form-select">
            <option value="">Estado: todos</option>
            @foreach(['preparando','enviado','entregado','devuelto'] as $e)
            <option value="{{ $e }}" {{ request('estado')===$e ? 'selected':'' }}>{{ ucfirst($e) }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2"><button class="btn btn-outline-primary w-100"><i class="bi bi-search me-1"></i>Filtrar</button></div>
</form>
</div></div>
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light"><tr><th>N° Envío</th><th>Pedido</th><th>Cliente</th><th>F. Empaque</th><th>F. Entrega</th><th class="text-center">Estado</th><th></th></tr></thead>
            <tbody>
            @forelse($envios as $e)
            @php $colores=['preparando'=>'bg-warning text-dark','enviado'=>'bg-info text-dark','entregado'=>'bg-success','devuelto'=>'bg-danger']; @endphp
            <tr>
                <td class="fw-semibold">{{ $e->numero_envio }}</td>
                <td>{{ $e->pedido->numero_referencia ?? '—' }}</td>
                <td>{{ $e->pedido->cliente->nombre ?? '—' }}</td>
                <td>{{ $e->fecha_empaque->format('d/m/Y') }}</td>
                <td>{{ $e->fecha_entrega?->format('d/m/Y') ?? '—' }}</td>
                <td class="text-center"><span class="badge {{ $colores[$e->estado] ?? 'bg-secondary' }}">{{ ucfirst($e->estado) }}</span></td>
                <td><a href="{{ route('envios.show',$e) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a></td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center py-5 text-muted">Sin envíos registrados</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($envios->hasPages())<div class="card-footer py-2">{{ $envios->links() }}</div>@endif
</div>
@endsection
