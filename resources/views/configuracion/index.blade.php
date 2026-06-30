@extends('layouts.app')
@section('titulo','Configuracion')
@section('contenido')

<ul class="nav nav-tabs mb-4">
    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#tab-empresa"><i class="bi bi-building me-1"></i>Empresa</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-sistema"><i class="bi bi-gear me-1"></i>Sistema</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-facturacion"><i class="bi bi-receipt me-1"></i>Facturación</a></li>
</ul>

<div class="tab-content">

{{-- TAB EMPRESA --}}
<div class="tab-pane fade show active" id="tab-empresa">
<div class="row justify-content-center"><div class="col-lg-9">
<div class="card">
<div class="card-header fw-semibold"><i class="bi bi-building text-primary me-2"></i>Datos de la Empresa</div>
<div class="card-body">
<form method="POST" action="{{ route('configuracion.empresa') }}">@csrf
<div class="row g-3">
    <div class="col-md-8">
        <label class="form-label fw-semibold">Nombre de la Empresa *</label>
        <input type="text" name="empresa_nombre" class="form-control @error('empresa_nombre') is-invalid @enderror"
            value="{{ old('empresa_nombre', $config['empresa_nombre']) }}" required>
        @error('empresa_nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-8"><label class="form-label fw-semibold">Nombre de Fantasía</label>
        <input type="text" name="empresa_nombre_fantasia" class="form-control" value="{{ old('empresa_nombre_fantasia', $config['empresa_nombre_fantasia']) }}"></div>
    <div class="col-md-3"><label class="form-label fw-semibold">RUC</label>
        <input type="text" name="empresa_ruc" class="form-control" placeholder="5054287" value="{{ old('empresa_ruc', $config['empresa_ruc']) }}"></div>
    <div class="col-md-1"><label class="form-label fw-semibold">DV</label>
        <input type="text" name="empresa_dv" class="form-control" maxlength="2" placeholder="7" value="{{ old('empresa_dv', $config['empresa_dv']) }}"></div>
    <div class="col-md-4"><label class="form-label fw-semibold">Telefono</label>
        <input type="text" name="empresa_telefono" class="form-control" value="{{ old('empresa_telefono', $config['empresa_telefono']) }}"></div>
    <div class="col-md-4"><label class="form-label fw-semibold">Email</label>
        <input type="email" name="empresa_email" class="form-control" value="{{ old('empresa_email', $config['empresa_email']) }}"></div>
    <div class="col-md-4"><label class="form-label fw-semibold">Sitio Web</label>
        <input type="text" name="empresa_web" class="form-control" value="{{ old('empresa_web', $config['empresa_web']) }}"></div>
    <div class="col-12"><label class="form-label fw-semibold">Direccion</label>
        <input type="text" name="empresa_direccion" class="form-control" value="{{ old('empresa_direccion', $config['empresa_direccion']) }}"></div>
    <div class="col-md-4"><label class="form-label fw-semibold">Ciudad</label>
        <input type="text" name="empresa_ciudad" class="form-control" value="{{ old('empresa_ciudad', $config['empresa_ciudad']) }}"></div>
    <div class="col-md-4"><label class="form-label fw-semibold">Pais</label>
        <input type="text" name="empresa_pais" class="form-control" value="{{ old('empresa_pais', $config['empresa_pais']) }}"></div>
    <div class="col-md-2"><label class="form-label fw-semibold">Moneda</label>
        <input type="text" name="empresa_moneda" class="form-control" maxlength="5" placeholder="COP" value="{{ old('empresa_moneda', $config['empresa_moneda']) }}"></div>
    <div class="col-md-2"><label class="form-label fw-semibold">Simbolo</label>
        <input type="text" name="empresa_simbolo" class="form-control" maxlength="3" placeholder="$" value="{{ old('empresa_simbolo', $config['empresa_simbolo']) }}"></div>
</div>
<div class="mt-4 pt-3 border-top">
    <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i>Guardar Empresa</button>
</div>
</form>
</div></div>
</div></div>
</div>

{{-- TAB SISTEMA --}}
<div class="tab-pane fade" id="tab-sistema">
<div class="row justify-content-center"><div class="col-lg-7">
<div class="card">
<div class="card-header fw-semibold"><i class="bi bi-sliders text-primary me-2"></i>Configuracion del Sistema</div>
<div class="card-body">
<form method="POST" action="{{ route('configuracion.sistema') }}">@csrf
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label fw-semibold">Zona Horaria</label>
        <select name="sistema_timezone" class="form-select">
            @foreach(['America/Bogota','America/Lima','America/Santiago','America/Argentina/Buenos_Aires','America/Mexico_City','America/Caracas','America/La_Paz','America/Guayaquil','America/Asuncion','America/Montevideo'] as $tz)
            <option value="{{ $tz }}" {{ $config['sistema_timezone']===$tz ? 'selected' : '' }}>{{ $tz }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label fw-semibold">Decimales en precios</label>
        <select name="sistema_decimales" class="form-select">
            <option value="0" {{ $config['sistema_decimales']==='0' ? 'selected':'' }}>Sin decimales</option>
            <option value="2" {{ $config['sistema_decimales']==='2' ? 'selected':'' }}>2 decimales</option>
            <option value="3" {{ $config['sistema_decimales']==='3' ? 'selected':'' }}>3 decimales</option>
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label fw-semibold">Alerta stock bajo</label>
        <div class="input-group">
            <input type="number" name="sistema_stock_minimo" class="form-control" min="0"
                value="{{ old('sistema_stock_minimo', $config['sistema_stock_minimo']) }}">
            <span class="input-group-text">unid.</span>
        </div>
        <small class="text-muted">El panel alerta cuando el stock sea menor a este valor</small>
    </div>
</div>

<div class="mt-4 p-3 bg-light rounded border">
    <h6 class="fw-semibold mb-2">Resumen actual de la configuracion</h6>
    <div class="row g-2 small">
        <div class="col-md-6"><span class="text-muted">Empresa:</span> <strong>{{ $config['empresa_nombre'] }}</strong></div>
        <div class="col-md-6"><span class="text-muted">RUC/NIT:</span> {{ $config['empresa_ruc'] ?: 'Sin registrar' }}</div>
        <div class="col-md-6"><span class="text-muted">Moneda:</span> {{ $config['empresa_moneda'] }} ({{ $config['empresa_simbolo'] }})</div>
        <div class="col-md-6"><span class="text-muted">Zona horaria:</span> {{ $config['sistema_timezone'] }}</div>
        <div class="col-md-6"><span class="text-muted">Ciudad:</span> {{ $config['empresa_ciudad'] ?: 'Sin registrar' }}, {{ $config['empresa_pais'] }}</div>
        <div class="col-md-6"><span class="text-muted">Email:</span> {{ $config['empresa_email'] ?: 'Sin registrar' }}</div>
    </div>
</div>

<div class="mt-4 pt-3 border-top">
    <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i>Guardar Sistema</button>
</div>
</form>
</div></div>
</div></div>
</div>

{{-- TAB FACTURACION --}}
<div class="tab-pane fade" id="tab-facturacion">
<div class="row justify-content-center"><div class="col-lg-7">

@if($config['fact_modo'] === 'local')
<div class="alert alert-warning"><i class="bi bi-exclamation-triangle me-2"></i>
<strong>Modo local activo:</strong> las facturas, notas de remisión y notas de crédito que generes aquí
<strong>no son documentos tributarios electrónicos válidos</strong> ante la SET. Se imprimen marcadas como
"documento interno" hasta que conectes el certificado digital y actives el modo electrónico.
</div>
@else
<div class="alert alert-success"><i class="bi bi-patch-check me-2"></i>
<strong>Modo electrónico activo:</strong> los documentos se firman y envían a SIFEN.
</div>
@endif

<div class="card">
<div class="card-header fw-semibold"><i class="bi bi-receipt text-primary me-2"></i>Datos de Facturación Electrónica (SIFEN)</div>
<div class="card-body">
<form method="POST" action="{{ route('configuracion.facturacion') }}">@csrf
<div class="row g-3">
    <div class="col-md-6"><label class="form-label fw-semibold">Timbrado N°</label>
        <input type="text" name="fact_timbrado" class="form-control" placeholder="18174154"
            value="{{ old('fact_timbrado', $config['fact_timbrado']) }}"></div>
    <div class="col-md-6"><label class="form-label fw-semibold">Fecha Inicio Vigencia</label>
        <input type="date" name="fact_fecha_inicio_vigencia" class="form-control"
            value="{{ old('fact_fecha_inicio_vigencia', $config['fact_fecha_inicio_vigencia']) }}"></div>
    <div class="col-md-4"><label class="form-label fw-semibold">Establecimiento</label>
        <input type="text" name="fact_establecimiento" class="form-control" maxlength="3" placeholder="001"
            value="{{ old('fact_establecimiento', $config['fact_establecimiento']) }}" required></div>
    <div class="col-md-4"><label class="form-label fw-semibold">Punto de Expedición</label>
        <input type="text" name="fact_punto_expedicion" class="form-control" maxlength="3" placeholder="001"
            value="{{ old('fact_punto_expedicion', $config['fact_punto_expedicion']) }}" required></div>
    <div class="col-md-4"><label class="form-label fw-semibold">Modo de Emisión</label>
        <select name="fact_modo" class="form-select" disabled>
            <option value="local" selected>Local (demo)</option>
            <option value="electronico">Electrónico (SIFEN)</option>
        </select>
        <input type="hidden" name="fact_modo" value="local">
        <small class="text-muted">Disponible al conectar certificado digital</small>
    </div>
</div>
<div class="mt-3 p-3 bg-light rounded border small">
    <strong>Próximo número de documento:</strong>
    {{ $config['fact_establecimiento'] }}-{{ $config['fact_punto_expedicion'] }}-{{ str_pad(\App\Models\Factura::count() + 1, 7, '0', STR_PAD_LEFT) }}
</div>
<div class="mt-4 pt-3 border-top">
    <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i>Guardar Facturación</button>
</div>
</form>
</div></div>

</div></div>
</div>

</div>
@endsection
