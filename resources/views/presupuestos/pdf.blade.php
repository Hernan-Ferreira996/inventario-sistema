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
.doc-box { text-align: right; }
.doc-box .titulo { font-size: 15px; font-weight: bold; }
.numero-doc { font-size: 14px; font-weight: bold; margin-top: 4px; }
.datos-cliente td { padding: 3px 8px; border-top: 1px solid #333; font-size: 10px; }
.tabla-items th { background: #e9e9e9; border: 1px solid #333; padding: 4px 6px; font-size: 9.5px; }
.tabla-items td { border: 1px solid #333; padding: 4px 6px; font-size: 10px; }
.totales td { border: 1px solid #333; padding: 4px 6px; font-weight: bold; }
.pie { margin-top: 12px; font-size: 9px; text-align: center; color: #444; }
.comentarios { margin-top: 10px; font-size: 10px; }
.text-end { text-align: right; }
.text-center { text-align: center; }
</style>
</head>
<body>

<table class="recuadro">
<tr class="cabecera">
    <td style="width:60%">
        <div class="empresa-nombre">{{ strtoupper($config['empresa_nombre_fantasia'] ?: $config['empresa_nombre']) }}</div>
        <div>de {{ strtoupper($config['empresa_nombre']) }}</div>
        <div>{{ $config['empresa_direccion'] }}</div>
        <div>{{ $config['empresa_ciudad'] }} - Teléf. {{ $config['empresa_telefono'] }}</div>
        @if($config['empresa_email'])<div>{{ $config['empresa_email'] }}</div>@endif
    </td>
    <td class="doc-box" style="width:40%">
        <div class="titulo">PRESUPUESTO</div>
        <div class="numero-doc">{{ $presupuesto->numero_documento }}</div>
    </td>
</tr>
</table>

<table class="recuadro" style="border-top:none">
<tr class="datos-cliente">
    <td style="width:33%"><strong>Fecha de emisión:</strong> {{ $presupuesto->fecha_emision->format('d/m/Y') }}</td>
    <td style="width:33%"><strong>Válido hasta:</strong> {{ $presupuesto->fecha_validez?->format('d/m/Y') ?? 'Sin límite' }}</td>
    <td style="width:34%"><strong>Cliente:</strong> {{ strtoupper($presupuesto->cliente->nombre ?? '') }}</td>
</tr>
@if($presupuesto->cliente?->direccion || $presupuesto->cliente?->email)
<tr class="datos-cliente">
    <td colspan="3"><strong>Dirección:</strong> {{ $presupuesto->cliente->direccion ?? '' }}
        &nbsp;&nbsp;<strong>Email:</strong> {{ $presupuesto->cliente->email ?? '' }}</td>
</tr>
@endif
</table>

<table class="tabla-items" style="margin-top:6px">
<thead>
<tr>
    <th style="width:6%">N°</th>
    <th style="width:10%">Cant.</th>
    <th style="width:40%">Descripción</th>
    <th style="width:16%">P. Unitario</th>
    <th style="width:10%">Desc.</th>
    <th style="width:18%">Subtotal</th>
</tr>
</thead>
<tbody>
@foreach($presupuesto->detalles as $d)
<tr>
    <td class="text-center">{{ $loop->iteration }}</td>
    <td class="text-center">{{ number_format($d->cantidad, 2) }}</td>
    <td>{{ $d->producto->nombre ?? 'Producto' }}</td>
    <td class="text-end">{{ number_format($d->precio_unitario, 0, ',', '.') }}</td>
    <td class="text-end">{{ $d->descuento }}%</td>
    <td class="text-end">{{ number_format($d->subtotal, 0, ',', '.') }}</td>
</tr>
@endforeach
</tbody>
</table>

<table class="totales" style="margin-top:4px">
<tr>
    <td style="width:82%" class="text-end">TOTAL:</td>
    <td style="width:18%" class="text-end">{{ $config['empresa_simbolo'] }} {{ number_format($presupuesto->total, 0, ',', '.') }}</td>
</tr>
</table>

@if($presupuesto->comentarios)
<div class="comentarios"><strong>Comentarios:</strong> {{ $presupuesto->comentarios }}</div>
@endif

<div class="pie">
    Este documento es un presupuesto y no constituye un comprobante fiscal ni una factura.<br>
    Los precios y condiciones son válidos únicamente hasta la fecha indicada.
</div>

</body>
</html>
