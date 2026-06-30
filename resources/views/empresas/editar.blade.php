@extends('layouts.app')
@section('titulo','Editar Empresa')
@section('contenido')
<div class="row justify-content-center"><div class="col-lg-9">
<div class="card"><div class="card-header fw-semibold">Editar: {{ $empresa->nombre_fantasia ?: $empresa->nombre }}</div>
<div class="card-body">
<form method="POST" action="{{ route('empresas.update',$empresa) }}">@csrf @method('PATCH')
<h6 class="fw-bold border-bottom pb-2 mb-3">Datos de la Empresa</h6>
<div class="row g-3">
    <div class="col-md-8"><label class="form-label fw-semibold">Razón Social *</label>
        <input type="text" name="nombre" class="form-control" value="{{ old('nombre',$empresa->nombre) }}" required></div>
    <div class="col-md-4"><label class="form-label fw-semibold">Nombre de Fantasía</label>
        <input type="text" name="nombre_fantasia" class="form-control" value="{{ old('nombre_fantasia',$empresa->nombre_fantasia) }}"></div>
    <div class="col-md-3"><label class="form-label fw-semibold">RUC *</label>
        <input type="text" name="ruc" class="form-control" value="{{ old('ruc',$empresa->ruc) }}" required></div>
    <div class="col-md-1"><label class="form-label fw-semibold">DV *</label>
        <input type="text" name="dv" class="form-control" value="{{ old('dv',$empresa->dv) }}" maxlength="2" required></div>
    <div class="col-md-4"><label class="form-label fw-semibold">Teléfono</label>
        <input type="text" name="telefono" class="form-control" value="{{ old('telefono',$empresa->telefono) }}"></div>
    <div class="col-md-4"><label class="form-label fw-semibold">Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email',$empresa->email) }}"></div>
    <div class="col-12"><label class="form-label fw-semibold">Dirección</label>
        <input type="text" name="direccion" class="form-control" value="{{ old('direccion',$empresa->direccion) }}"></div>
    <div class="col-md-4"><label class="form-label fw-semibold">Ciudad</label>
        <input type="text" name="ciudad" class="form-control" value="{{ old('ciudad',$empresa->ciudad) }}"></div>
    <div class="col-md-4"><label class="form-label fw-semibold">País *</label>
        <input type="text" name="pais" class="form-control" value="{{ old('pais',$empresa->pais) }}" required></div>
    <div class="col-md-2"><label class="form-label fw-semibold">Moneda *</label>
        <input type="text" name="moneda" class="form-control" value="{{ old('moneda',$empresa->moneda) }}" required></div>
    <div class="col-md-2"><label class="form-label fw-semibold">Símbolo *</label>
        <input type="text" name="simbolo" class="form-control" value="{{ old('simbolo',$empresa->simbolo) }}" required></div>
</div>
<h6 class="fw-bold border-bottom pb-2 mb-3 mt-4">Facturación Electrónica (SIFEN)</h6>
<div class="row g-3">
    <div class="col-md-4"><label class="form-label fw-semibold">Timbrado N°</label>
        <input type="text" name="fact_timbrado" class="form-control" value="{{ old('fact_timbrado',$empresa->fact_timbrado) }}"></div>
    <div class="col-md-4"><label class="form-label fw-semibold">Fecha Inicio Vigencia</label>
        <input type="date" name="fact_fecha_inicio_vigencia" class="form-control" value="{{ $empresa->fact_fecha_inicio_vigencia?->format('Y-m-d') }}"></div>
    <div class="col-md-2"><label class="form-label fw-semibold">Establecimiento</label>
        <input type="text" name="fact_establecimiento" class="form-control" value="{{ old('fact_establecimiento',$empresa->fact_establecimiento) }}" maxlength="3"></div>
    <div class="col-md-2"><label class="form-label fw-semibold">Punto Expedición</label>
        <input type="text" name="fact_punto_expedicion" class="form-control" value="{{ old('fact_punto_expedicion',$empresa->fact_punto_expedicion) }}" maxlength="3"></div>
    <div class="col-md-4"><label class="form-label fw-semibold">Timezone</label>
        <input type="text" name="timezone" class="form-control" value="{{ old('timezone',$empresa->timezone) }}"></div>
    <div class="col-md-4"><label class="form-label fw-semibold">Decimales en precios</label>
        <select name="decimales" class="form-select">
            @foreach([0=>'Sin decimales',2=>'2 decimales',3=>'3 decimales'] as $v => $l)
            <option value="{{ $v }}" @selected($empresa->decimales == $v)>{{ $l }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4"><label class="form-label fw-semibold">Alerta stock bajo</label>
        <div class="input-group"><input type="number" name="stock_minimo" class="form-control" value="{{ old('stock_minimo',$empresa->stock_minimo) }}" min="0"><span class="input-group-text">unid.</span></div>
    </div>
</div>
<div class="d-flex gap-2 mt-4 pt-3 border-top">
    <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i>Actualizar</button>
    <a href="{{ route('empresas.show',$empresa) }}" class="btn btn-outline-secondary">Cancelar</a>
</div>
</form>
</div></div>
</div></div>
@endsection
