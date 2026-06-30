@extends('layouts.app')
@section('titulo','Nueva Empresa')
@section('contenido')
<div class="row justify-content-center"><div class="col-lg-9">
<div class="card"><div class="card-header fw-semibold"><i class="bi bi-building-add me-2 text-primary"></i>Nueva Empresa</div>
<div class="card-body">
<form method="POST" action="{{ route('empresas.store') }}">@csrf
<h6 class="fw-bold border-bottom pb-2 mb-3">Datos de la Empresa</h6>
<div class="row g-3">
    <div class="col-md-8"><label class="form-label fw-semibold">Razón Social *</label>
        <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" required></div>
    <div class="col-md-4"><label class="form-label fw-semibold">Nombre de Fantasía</label>
        <input type="text" name="nombre_fantasia" class="form-control" value="{{ old('nombre_fantasia') }}"></div>
    <div class="col-md-3"><label class="form-label fw-semibold">RUC *</label>
        <input type="text" name="ruc" class="form-control" value="{{ old('ruc') }}" required placeholder="5054287"></div>
    <div class="col-md-1"><label class="form-label fw-semibold">DV *</label>
        <input type="text" name="dv" class="form-control" value="{{ old('dv') }}" maxlength="2" required placeholder="7"></div>
    <div class="col-md-4"><label class="form-label fw-semibold">Teléfono</label>
        <input type="text" name="telefono" class="form-control" value="{{ old('telefono') }}"></div>
    <div class="col-md-4"><label class="form-label fw-semibold">Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email') }}"></div>
    <div class="col-12"><label class="form-label fw-semibold">Dirección</label>
        <input type="text" name="direccion" class="form-control" value="{{ old('direccion') }}"></div>
    <div class="col-md-4"><label class="form-label fw-semibold">Ciudad</label>
        <input type="text" name="ciudad" class="form-control" value="{{ old('ciudad') }}"></div>
    <div class="col-md-4"><label class="form-label fw-semibold">País *</label>
        <input type="text" name="pais" class="form-control" value="{{ old('pais','Paraguay') }}" required></div>
    <div class="col-md-2"><label class="form-label fw-semibold">Moneda *</label>
        <input type="text" name="moneda" class="form-control" value="{{ old('moneda','PYG') }}" required placeholder="PYG"></div>
    <div class="col-md-2"><label class="form-label fw-semibold">Símbolo *</label>
        <input type="text" name="simbolo" class="form-control" value="{{ old('simbolo','Gs.') }}" required placeholder="Gs."></div>
</div>
<h6 class="fw-bold border-bottom pb-2 mb-3 mt-4">Facturación Electrónica (SIFEN)</h6>
<div class="row g-3">
    <div class="col-md-4"><label class="form-label fw-semibold">Timbrado N°</label>
        <input type="text" name="fact_timbrado" class="form-control" value="{{ old('fact_timbrado') }}" placeholder="18174154"></div>
    <div class="col-md-4"><label class="form-label fw-semibold">Fecha Inicio Vigencia</label>
        <input type="date" name="fact_fecha_inicio_vigencia" class="form-control" value="{{ old('fact_fecha_inicio_vigencia') }}"></div>
    <div class="col-md-2"><label class="form-label fw-semibold">Establecimiento *</label>
        <input type="text" name="fact_establecimiento" class="form-control" value="{{ old('fact_establecimiento','001') }}" required maxlength="3"></div>
    <div class="col-md-2"><label class="form-label fw-semibold">Punto Expedición *</label>
        <input type="text" name="fact_punto_expedicion" class="form-control" value="{{ old('fact_punto_expedicion','001') }}" required maxlength="3"></div>
</div>
<div class="d-flex gap-2 mt-4 pt-3 border-top">
    <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i>Crear Empresa</button>
    <a href="{{ route('empresas.index') }}" class="btn btn-outline-secondary">Cancelar</a>
</div>
</form>
</div></div>
</div></div>
@endsection
