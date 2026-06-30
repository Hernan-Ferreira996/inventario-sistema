@extends('layouts.app')
@section('titulo','Notas de Crédito')
@section('contenido')
<h5 class="mb-3">Notas de Crédito</h5>
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light"><tr>
                <th>N° Documento</th><th>Factura Asociada</th><th>Cliente</th><th>Fecha</th><th>Motivo</th><th class="text-end">Total</th><th>Acciones</th>
            </tr></thead>
            <tbody>
            @forelse($notas as $n)
            <tr>
                <td class="fw-semibold">{{ $n->numero_completo }}</td>
                <td>{{ $n->factura->numero_documento ?? '—' }}</td>
                <td>{{ $n->factura->pedido->cliente->nombre ?? '—' }}</td>
                <td>{{ $n->fecha_emision->format('d/m/Y') }}</td>
                <td>{{ ucfirst(str_replace('_',' ',$n->motivo)) }}</td>
                <td class="text-end fw-semibold">{{ number_format($n->total,0,',','.') }}</td>
                <td><div class="d-flex gap-1">
                    <a href="{{ route('notas-credito.show',$n) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                    <a href="{{ route('notas-credito.pdf',$n) }}" target="_blank" class="btn btn-sm btn-outline-secondary"><i class="bi bi-file-pdf"></i></a>
                </div></td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center py-5 text-muted">Sin notas de crédito generadas</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($notas->hasPages())<div class="card-footer py-2">{{ $notas->links() }}</div>@endif
</div>
@endsection
