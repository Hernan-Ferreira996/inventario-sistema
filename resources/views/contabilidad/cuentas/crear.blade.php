@extends('layouts.app')
@section('titulo','Nueva Cuenta Contable')
@section('contenido')
<div class="row justify-content-center"><div class="col-lg-7">
<div class="card"><div class="card-header fw-semibold">Nueva Cuenta Contable</div><div class="card-body">
<form method="POST" action="{{ route('contabilidad.cuentas.store') }}">@csrf
<div class="row g-3">
    <div class="col-md-4"><label class="form-label fw-semibold">Código *</label>
        <input type="text" name="codigo" class="form-control" placeholder="1.1.06" value="{{ old('codigo') }}" required></div>
    <div class="col-md-8"><label class="form-label fw-semibold">Nombre *</label>
        <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" required></div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Tipo *</label>
        <select name="tipo" class="form-select" required>
            @foreach(\App\Models\CatalogoValor::paraGrupo('cuentas_contables.tipo') as $t)
            <option value="{{ $t->codigo }}">{{ $t->etiqueta }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Naturaleza *</label>
        <select name="naturaleza" class="form-select" required>
            <option value="deudora">Deudora (Activo/Gasto)</option>
            <option value="acreedora">Acreedora (Pasivo/Patrimonio/Ingreso)</option>
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Cuenta Padre</label>
        <select name="cuenta_padre_id" class="form-select">
            <option value="">-- Ninguna (cuenta raíz) --</option>
            @foreach($cuentasPadre as $p)
            <option value="{{ $p->id }}">{{ $p->codigo }} - {{ $p->nombre }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-12"><div class="form-check">
        <input type="checkbox" name="imputable" value="1" class="form-check-input" checked>
        <label class="form-check-label">Imputable (recibe movimientos directos). Desmarcar si es una cuenta de título que solo agrupa subcuentas.</label>
    </div></div>
</div>
<div class="d-flex gap-2 mt-4">
    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Guardar</button>
    <a href="{{ route('contabilidad.cuentas.index') }}" class="btn btn-outline-secondary">Cancelar</a>
</div>
</form>
</div></div>
</div></div>
@endsection
