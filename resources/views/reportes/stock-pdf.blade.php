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
.text-danger { color: #dc3545; }
.fecha { color: #666; font-size: 9px; margin-bottom: 10px; }
</style>
</head>
<body>
<h2>Reporte de Stock</h2>
<div class="fecha">Generado el {{ now()->format('d/m/Y H:i') }}</div>
<table>
<thead><tr><th>Código</th><th>Nombre</th><th>Categoría</th><th>Unidad</th><th class="text-end">Stock</th><th class="text-end">P. Compra</th><th class="text-end">P. Venta</th><th class="text-end">Valor en Stock</th></tr></thead>
<tbody>
@foreach($productos as $p)
@php $stock = $p->movimientos_sum_cantidad ?? 0; @endphp
<tr>
    <td>{{ $p->codigo }}</td>
    <td>{{ $p->nombre }}</td>
    <td>{{ $p->categoria->nombre ?? '—' }}</td>
    <td>{{ $p->unidad->nombre ?? '—' }}</td>
    <td class="text-end {{ $stock <= 0 ? 'text-danger' : '' }}">{{ number_format($stock,2) }}</td>
    <td class="text-end">{{ number_format($p->precio_compra,2) }}</td>
    <td class="text-end">{{ number_format($p->precio_venta_minorista,2) }}</td>
    <td class="text-end">{{ number_format($stock * $p->precio_compra,2) }}</td>
</tr>
@endforeach
</tbody>
</table>
</body>
</html>
