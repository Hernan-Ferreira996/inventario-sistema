@extends('layouts.app')
@section('titulo','Presupuestos')
@section('contenido')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Presupuestos</h5>
    @can('pedidos.crear')
    @if(!Auth::user()?->esSuperAdmin())
    <a href="{{ route('presupuestos.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Nuevo Presupuesto</a>
    @endif
    @endcan
</div>

<div class="card mb-3"><div class="card-body py-2">
<form method="GET" class="row g-2 align-items-center">
    <div class="col-md-5"><input type="text" name="q" class="form-control" placeholder="Buscar por N° o cliente..." value="{{ request('q') }}"></div>
    <div class="col-md-3">
        <select name="estado" class="form-select">
            <option value="">Todos los estados</option>
            @foreach(\App\Models\CatalogoValor::codigos('presupuestos.estado') as $e)
            <option value="{{ $e }}" {{ request('estado')===$e ? 'selected' : '' }}>{{ \App\Models\CatalogoValor::etiqueta('presupuestos.estado', $e) }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2"><button type="submit" class="btn btn-outline-primary w-100"><i class="bi bi-search me-1"></i>Filtrar</button></div>
    <div class="col-md-2"><a href="{{ route('presupuestos.index') }}" class="btn btn-outline-secondary w-100">Limpiar</a></div>
</form>
</div></div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light"><tr>
                <th>N° Documento</th><th>Cliente</th><th>Fecha</th><th>Validez</th><th class="text-end">Total</th><th class="text-center">Estado</th><th class="text-center">Etapa</th><th>Acciones</th>
            </tr></thead>
            <tbody>
            @forelse($presupuestos as $p)
            <tr>
                <td class="fw-semibold"><a href="{{ route('presupuestos.show',$p) }}" class="text-decoration-none">{{ $p->numero_documento }}</a></td>
                <td>{{ $p->cliente->nombre ?? '—' }}</td>
                <td>{{ $p->fecha_emision->format('d/m/Y') }}</td>
                <td>{{ $p->fecha_validez?->format('d/m/Y') ?? '—' }}</td>
                <td class="text-end fw-semibold">{{ number_format($p->total,0,',','.') }}</td>
                <td class="text-center"><x-badge-estado grupo="presupuestos.estado" :valor="$p->estado" /></td>
                <td class="text-center"><x-badge-estado grupo="presupuestos.etapa" :valor="$p->etapa" /></td>
                <td><div class="d-flex gap-1">
                    <a href="{{ route('presupuestos.show',$p) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                    <a href="{{ route('presupuestos.pdf',$p) }}" target="_blank" class="btn btn-sm btn-outline-secondary"><i class="bi bi-file-pdf"></i></a>
                    @can('pedidos.editar')
                    @if($p->estado !== 'convertido')
                    <a href="{{ route('presupuestos.edit',$p) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                    @endif
                    @endcan
                </div></td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center py-5 text-muted">
                <i class="bi bi-file-earmark-text d-block mb-2" style="font-size:2rem"></i>
                Sin presupuestos. @if(!Auth::user()?->esSuperAdmin())<a href="{{ route('presupuestos.create') }}">Crear el primero</a>@endif
            </td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($presupuestos->hasPages())<div class="card-footer py-2">{{ $presupuestos->links() }}</div>@endif
</div>
@endsection
