@extends('layouts.app')
@section('titulo','Rendiciones')
@section('contenido')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Rendiciones de Caja</h5>
    <a href="{{ route('rendiciones.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Nueva Rendición</a>
</div>
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light"><tr>
                <th>Fecha</th><th>Caja</th><th>Cobrador</th><th class="text-end">Monto Total</th><th>Registrada por</th><th></th>
            </tr></thead>
            <tbody>
            @forelse($rendiciones as $r)
            <tr>
                <td>{{ $r->fecha->format('d/m/Y') }}</td>
                <td>{{ $r->caja->nombre ?? '—' }}</td>
                <td>{{ $r->cobrador->name ?? '—' }}</td>
                <td class="text-end fw-semibold">{{ number_format($r->monto_total,0,',','.') }}</td>
                <td class="text-muted small">{{ $r->usuario->name ?? '—' }}</td>
                <td><a href="{{ route('rendiciones.show',$r) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a></td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center py-5 text-muted">
                <i class="bi bi-wallet2 d-block mb-2" style="font-size:2rem"></i>
                Sin rendiciones registradas. <a href="{{ route('rendiciones.create') }}">Crear la primera</a>
            </td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($rendiciones->hasPages())<div class="card-footer py-2">{{ $rendiciones->links() }}</div>@endif
</div>
@endsection
