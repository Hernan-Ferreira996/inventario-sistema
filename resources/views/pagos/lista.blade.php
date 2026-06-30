@extends('layouts.app')
@section('titulo','Pagos')
@section('contenido')
<h5 class="mb-3">Pagos Registrados</h5>
<div class="card mb-3"><div class="card-body py-2">
<form method="GET"><input type="text" name="q" class="form-control" placeholder="Buscar por factura o cliente..." value="{{ request('q') }}"></form>
</div></div>
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light"><tr><th>Factura</th><th>Cliente</th><th>Fecha</th><th>Método</th><th class="text-end">Monto</th><th>Acciones</th></tr></thead>
            <tbody>
            @forelse($pagos as $p)
            <tr>
                <td class="fw-semibold">{{ $p->factura->numero_documento ?? '—' }}</td>
                <td>{{ $p->factura->pedido->cliente->nombre ?? '—' }}</td>
                <td>{{ $p->fecha_pago->format('d/m/Y') }}</td>
                <td>{{ $p->metodoPago->nombre ?? '—' }}</td>
                <td class="text-end fw-semibold text-success">{{ number_format($p->monto,0,',','.') }}</td>
                <td><div class="d-flex gap-1">
                    <a href="{{ route('pagos.show',$p) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                    @can('pagos.eliminar')
                    <form method="POST" action="{{ route('pagos.destroy',$p) }}" onsubmit="return confirm('¿Eliminar pago? El saldo de la factura se restaurará.')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                    </form>
                    @endcan
                </div></td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center py-5 text-muted">Sin pagos registrados</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($pagos->hasPages())<div class="card-footer py-2">{{ $pagos->links() }}</div>@endif
</div>
@endsection
