<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
@page { margin: 20px; }
body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #1a1a1a; }
table { width: 100%; border-collapse: collapse; }
.recuadro { border: 1.5px solid #333; }
.cabecera td { vertical-align: top; padding: 8px; }
.empresa-nombre { font-size: 13px; font-weight: bold; }
.timbrado-box { text-align: right; }
.timbrado-box .titulo { font-size: 13px; font-weight: bold; }
.numero-doc { font-size: 14px; font-weight: bold; }
.datos td { padding: 3px 8px; border-top: 1px solid #333; font-size: 10px; }
.tabla-items th { background: #e9e9e9; border: 1px solid #333; padding: 4px 6px; font-size: 9.5px; }
.tabla-items td { border: 1px solid #333; padding: 4px 6px; font-size: 10px; }
.demo-banner { background: #fff3cd; border: 2px solid #f0ad4e; padding: 8px; text-align: center; font-weight: bold; font-size: 11px; margin-bottom: 10px; }
.text-end { text-align: right; }
.text-center { text-align: center; }
</style>
</head>
<body>

@if($config['fact_modo'] === 'local')
<div class="demo-banner">*** DOCUMENTO INTERNO — NO VÁLIDO COMO COMPROBANTE TRIBUTARIO ELECTRÓNICO ***</div>
@endif

<table class="recuadro">
<tr class="cabecera">
    <td style="width:60%">
        <div class="empresa-nombre">{{ strtoupper($config['empresa_nombre_fantasia'] ?: $config['empresa_nombre']) }}</div>
        <div>de {{ strtoupper($config['empresa_nombre']) }}</div>
        <div>RUC {{ $config['empresa_ruc'] }}-{{ $config['empresa_dv'] }}</div>
    </td>
    <td class="timbrado-box" style="width:40%">
        <div class="titulo">TIMBRADO N° {{ $config['fact_timbrado'] ?: 'SIN ASIGNAR' }}</div>
        <div style="margin-top:4px">NOTA DE REMISIÓN {{ $config['fact_modo'] === 'local' ? '(DEMO)' : 'ELECTRÓNICA' }}</div>
        <div class="numero-doc">{{ $notaRemision->numero_completo }}</div>
    </td>
</tr>
</table>

<table class="recuadro" style="border-top:none">
<tr class="datos"><td style="width:50%"><strong>Fecha:</strong> {{ $notaRemision->fecha_emision->format('d/m/Y') }}</td>
<td style="width:50%"><strong>Motivo:</strong> {{ ucfirst($notaRemision->motivo) }}</td></tr>
<tr class="datos"><td colspan="2"><strong>Destinatario:</strong> {{ strtoupper($notaRemision->pedido->cliente->nombre ?? '') }}</td></tr>
<tr class="datos"><td><strong>Dirección destino:</strong> {{ $notaRemision->direccion_destino }}</td>
<td><strong>Origen:</strong> {{ $notaRemision->ubicacionOrigen->nombre ?? '—' }}</td></tr>
<tr class="datos"><td><strong>Transportista:</strong> {{ $notaRemision->transportista }}</td>
<td><strong>Placa:</strong> {{ $notaRemision->vehiculo_placa }}</td></tr>
</table>

<table class="tabla-items" style="margin-top:6px">
<thead><tr><th style="width:15%">Cantidad</th><th style="width:85%">Descripción</th></tr></thead>
<tbody>
@foreach($notaRemision->detalles as $d)
<tr><td class="text-center">{{ number_format($d->cantidad,0) }}</td><td>{{ $d->producto->nombre ?? 'Producto' }}</td></tr>
@endforeach
</tbody>
</table>

</body>
</html>
