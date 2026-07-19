@extends('layouts.app')
@section('titulo','Notas de Remisión')
@section('contenido')
<h5 class="mb-3">Notas de Remisión</h5>
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light"><tr>
                <th>N° Documento</th><th>Origen</th><th>Destino</th><th>Fecha</th><th>Motivo</th><th>Acciones</th>
            </tr></thead>
            <tbody>
            @forelse($notas as $n)
            <tr>
                <td class="fw-semibold">{{ $n->numero_completo }}</td>
                <td>{{ $n->origen_referencia ?? '—' }} @if($n->presupuesto_id)<span class="badge bg-info text-dark">anticipo</span>@endif</td>
                <td>{{ $n->cliente->nombre ?? '—' }}</td>
                <td>{{ $n->fecha_emision->format('d/m/Y') }}</td>
                <td>{{ ucfirst($n->motivo) }}</td>
                <td><div class="d-flex gap-1">
                    <a href="{{ route('notas-remision.show',$n) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                    <a href="{{ route('notas-remision.pdf',$n) }}" target="_blank" class="btn btn-sm btn-outline-secondary"><i class="bi bi-file-pdf"></i></a>
                </div></td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center py-5 text-muted">Sin notas de remisión generadas</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($notas->hasPages())<div class="card-footer py-2">{{ $notas->links() }}</div>@endif
</div>
@endsection
