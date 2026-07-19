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
.datos td { padding: 4px 8px; border-top: 1px solid #333; font-size: 10.5px; }
.monto-box { text-align: center; padding: 14px; font-size: 18px; font-weight: bold; border: 1.5px solid #333; margin-top: 8px; }
.pie { margin-top: 16px; font-size: 9px; text-align: center; color: #444; }
</style>
</head>
<body>

<table class="recuadro">
<tr class="cabecera">
    <td style="width:60%">
        <div class="empresa-nombre">{{ strtoupper($config['empresa_nombre_fantasia'] ?: $config['empresa_nombre']) }}</div>
        <div>{{ $config['empresa_direccion'] }}</div>
        <div>{{ $config['empresa_ciudad'] }} - Teléf. {{ $config['empresa_telefono'] }}</div>
    </td>
    <td class="doc-box" style="width:40%">
        <div class="titulo">RECIBO DE PAGO</div>
        <div class="numero-doc">{{ $pago->numero_recibo }}</div>
    </td>
</tr>
</table>

<table class="recuadro" style="border-top:none">
<tr class="datos">
    <td style="width:50%"><strong>Fecha:</strong> {{ $pago->fecha_pago->format('d/m/Y') }}</td>
    <td style="width:50%"><strong>Factura:</strong> {{ $pago->factura->numero_documento ?? '—' }}</td>
</tr>
<tr class="datos">
    <td colspan="2"><strong>Recibí de:</strong> {{ strtoupper($pago->factura->pedido->cliente->nombre ?? '') }}</td>
</tr>
<tr class="datos">
    <td><strong>Método de Pago:</strong> {{ $pago->metodoPago->nombre ?? '—' }}</td>
    <td><strong>Caja:</strong> {{ $pago->caja->nombre ?? '—' }}</td>
</tr>
@if($pago->cobrador)
<tr class="datos">
    <td colspan="2"><strong>Cobrador:</strong> {{ $pago->cobrador->name }}</td>
</tr>
@endif
@if($pago->referencia)
<tr class="datos">
    <td colspan="2"><strong>Referencia:</strong> {{ $pago->referencia }}</td>
</tr>
@endif
</table>

<div class="monto-box">
    {{ $config['empresa_simbolo'] }} {{ number_format($pago->monto, 0, ',', '.') }}
</div>

<div class="pie">
    Recibo electrónico generado por el sistema. Documento en modo demo, sin validez tributaria.
</div>

</body>
</html>
