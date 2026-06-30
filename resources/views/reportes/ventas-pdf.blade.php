<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; }
h2 { margin-bottom: 4px; }
table { width: 100%; border-collapse: collapse; margin-top: 10px; }
th { background: #1e293b; color: #fff; padding: 5px 6px; text-align: left; font-size: 9.5px; }
td { padding: 4px 6px; border-bottom: 1px solid #ddd; font-size: 9.5px; }
.text-end { text-align: right; }
.fecha { color: #666; font-size: 9px; margin-bottom: 10px; }
tfoot td { font-weight: bold; border-top: 2px solid #333; }
</style>
</head>
<body>
<h2>Reporte de Ventas</h2>
<div class="fecha">Período: {{ \Carbon\Carbon::parse($desde)->format('d/m/Y') }} — {{ \Carbon\Carbon::parse($hasta)->format('d/m/Y') }} | Generado el {{ now()->format('d/m/Y H:i') }}</div>
<table>
<thead><tr><th>N° Referencia</th><th>Cliente</th><th>Fecha</th><th class="text-end">Total</th><th class="text-end">Pagado</th><th class="text-end">Saldo</th><th>Estado</th></tr></thead>
<tbody>
@foreach($pedidos as $p)
<tr>
    <td>{{ $p->numero_referencia }}</td>
    <td>{{ $p->cliente->nombre ?? '—' }}</td>
    <td>{{ $p->fecha_pedido->format('d/m/Y') }}</td>
    <td class="text-end">{{ number_format($p->total,2) }}</td>
    <td class="text-end">{{ number_format($p->monto_pagado,2) }}</td>
    <td class="text-end">{{ number_format($p->total - $p->monto_pagado,2) }}</td>
    <td>{{ ucfirst($p->estado) }}</td>
</tr>
@endforeach
</tbody>
<tfoot><tr><td colspan="3" class="text-end">TOTAL:</td><td class="text-end">{{ number_format($total,2) }}</td><td colspan="3"></td></tr></tfoot>
</table>
</body>
</html>
