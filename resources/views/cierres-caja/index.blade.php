@extends('layouts.app')
@section('titulo','Cierre de Caja')
@section('contenido')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Cierre de Caja</h5>
    <a href="{{ route('cierres-caja.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Nuevo Cierre</a>
</div>
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light"><tr>
                <th>Fecha</th><th>Caja</th><th class="text-end">Saldo Inicial</th><th class="text-end">Total Cobrado</th><th class="text-end">Saldo Final</th><th>Cerrado por</th>
            </tr></thead>
            <tbody>
            @forelse($cierres as $c)
            <tr>
                <td>{{ $c->fecha->format('d/m/Y') }}</td>
                <td>{{ $c->caja->nombre ?? '—' }}</td>
                <td class="text-end">{{ number_format($c->saldo_inicial,0,',','.') }}</td>
                <td class="text-end">{{ number_format($c->total_cobrado,0,',','.') }}</td>
                <td class="text-end fw-semibold">{{ number_format($c->saldo_final,0,',','.') }}</td>
                <td class="text-muted small">{{ $c->usuario->name ?? '—' }}</td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center py-5 text-muted">
                <i class="bi bi-cash-coin d-block mb-2" style="font-size:2rem"></i>
                Sin cierres de caja registrados. <a href="{{ route('cierres-caja.create') }}">Crear el primero</a>
            </td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($cierres->hasPages())<div class="card-footer py-2">{{ $cierres->links() }}</div>@endif
</div>
@endsection
