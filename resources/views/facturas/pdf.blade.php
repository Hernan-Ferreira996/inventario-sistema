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
.datos-cliente td { padding: 3px 8px; border-top: 1px solid #333; font-size: 10px; }
.tabla-items th { background: #e9e9e9; border: 1px solid #333; padding: 4px 6px; font-size: 9.5px; }
.tabla-items td { border: 1px solid #333; padding: 4px 6px; font-size: 10px; }
.totales td { border: 1px solid #333; padding: 4px 6px; font-weight: bold; }
.demo-banner { background: #fff3cd; border: 2px solid #f0ad4e; padding: 8px; text-align: center; font-weight: bold; font-size: 11px; margin-bottom: 10px; }
.pie { margin-top: 12px; font-size: 9px; text-align: center; color: #444; }
.text-end { text-align: right; }
.text-center { text-align: center; }
</style>
</head>
<body>

@if($config['fact_modo'] === 'local')
<div class="demo-banner">
    *** DOCUMENTO INTERNO — NO VÁLIDO COMO COMPROBANTE TRIBUTARIO ELECTRÓNICO ***<br>
    Generado en modo demostración. No reemplaza la factura emitida en e-Kuatia/SIFEN.
</div>
@endif

<table class="recuadro">
<tr class="cabecera">
    <td style="width:60%">
        <div class="empresa-nombre">{{ strtoupper($config['empresa_nombre_fantasia'] ?: $config['empresa_nombre']) }}</div>
        <div>de {{ strtoupper($config['empresa_nombre']) }}</div>
        <div>{{ $config['empresa_direccion'] }}</div>
        <div>{{ $config['empresa_ciudad'] }} - Teléf. {{ $config['empresa_telefono'] }}</div>
    </td>
    <td class="timbrado-box" style="width:40%">
        <div class="titulo">TIMBRADO N° {{ $config['fact_timbrado'] ?: 'SIN ASIGNAR' }}</div>
        @if($config['fact_fecha_inicio_vigencia'])
        <div>Fecha Inicio Vigencia: {{ \Carbon\Carbon::parse($config['fact_fecha_inicio_vigencia'])->format('d/m/Y') }}</div>
        @endif
        <div>RUC {{ $config['empresa_ruc'] }}-{{ $config['empresa_dv'] }}</div>
        <div style="margin-top:4px">FACTURA {{ $config['fact_modo'] === 'local' ? '(DEMO)' : 'ELECTRÓNICA' }}</div>
        <div class="numero-doc">{{ $factura->numero_documento }}</div>
    </td>
</tr>
</table>

<table class="recuadro" style="border-top:none">
<tr class="datos-cliente">
    <td style="width:50%"><strong>Fecha de emisión:</strong> {{ $factura->fecha_factura->format('d/m/Y') }}</td>
    <td style="width:50%"><strong>Condición de venta:</strong>
        {{ $factura->condicion_venta === 'contado' ? 'Contado ☑' : 'Crédito ☑' }}
    </td>
</tr>
<tr class="datos-cliente">
    <td><strong>RUC/Documento N°:</strong> {{ $factura->numero_documento_cliente ?: 'Sin datos' }}</td>
    <td><strong>Nombre o Razón Social:</strong> {{ strtoupper($factura->pedido->cliente->nombre ?? 'Cliente') }}</td>
</tr>
<tr class="datos-cliente">
    <td colspan="2"><strong>Dirección:</strong> {{ $factura->pedido->cliente->direccion ?? '' }}
        &nbsp;&nbsp;<strong>Email:</strong> {{ $factura->pedido->cliente->email ?? '' }}</td>
</tr>
</table>

<table class="tabla-items" style="margin-top:6px">
<thead>
<tr>
    <th style="width:6%">Cód.</th>
    <th style="width:8%">Cant.</th>
    <th style="width:36%">Descripción</th>
    <th style="width:14%">P. Unitario</th>
    <th style="width:10%">Desc.</th>
    <th style="width:13%">Exentas</th>
    <th style="width:13%">Gravadas</th>
</tr>
</thead>
<tbody>
@foreach($factura->pedido->detalles as $d)
<tr>
    <td class="text-center">{{ $loop->iteration }}</td>
    <td class="text-center">{{ number_format($d->cantidad, 0) }}</td>
    <td>{{ $d->producto->nombre ?? 'Producto' }}</td>
    <td class="text-end">{{ number_format($d->precio_unitario, 0, ',', '.') }}</td>
    <td class="text-end">{{ $d->descuento }}%</td>
    <td class="text-end">{{ $d->impuesto == 0 ? number_format($d->subtotal, 0, ',', '.') : '0' }}</td>
    <td class="text-end">{{ $d->impuesto > 0 ? number_format($d->subtotal, 0, ',', '.') : '0' }}</td>
</tr>
@endforeach
</tbody>
</table>

<table class="totales" style="margin-top:4px">
<tr>
    <td style="width:70%" class="text-end">SUBTOTAL:</td>
    <td style="width:30%" class="text-end">{{ number_format($factura->subtotal + $factura->monto_descuento, 0, ',', '.') }}</td>
</tr>
@if((float) $factura->descuento_global > 0)
<tr style="background:#f0fff0">
    <td class="text-end">DESCUENTO GLOBAL {{ $factura->descuento_global }}%:</td>
    <td class="text-end">- {{ number_format($factura->monto_descuento, 0, ',', '.') }}</td>
</tr>
<tr>
    <td class="text-end">SUBTOTAL CON DESCUENTO:</td>
    <td class="text-end">{{ number_format($factura->subtotal, 0, ',', '.') }}</td>
</tr>
@endif
<tr>
    <td class="text-end">TOTAL IVA:</td>
    <td class="text-end">{{ number_format($factura->impuesto_total, 0, ',', '.') }}</td>
</tr>
<tr>
    <td class="text-end">TOTAL A PAGAR:</td>
    <td class="text-end">{{ $config['empresa_simbolo'] }} {{ number_format($factura->total, 0, ',', '.') }}</td>
</tr>
</table>

<div class="pie">
@if($config['fact_modo'] === 'local')
    Este documento es una representación interna generada por el sistema y no constituye un comprobante fiscal válido.<br>
    La facturación electrónica oficial se emite a través de e-Kuatia / SIFEN.
@else
    Consulte la validez de este documento con el CDC en: https://ekuatia.set.gov.py/consultas/<br>
    CDC: {{ $factura->cdc }}
@endif
</div>

</body>
</html>
