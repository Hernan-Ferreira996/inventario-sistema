<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\PedidoVenta;
use App\Models\PedidoCompra;
use App\Models\Cliente;
use App\Models\MovimientoStock;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $hoy = Carbon::today();
        $inicioMes = Carbon::now()->startOfMonth();

        $estadisticas = Cache::remember('dashboard_stats', 900, function () use ($hoy, $inicioMes) {
            return [
                'total_productos'  => Producto::activos()->count(),
                'total_clientes'   => Cliente::where('activo', true)->count(),
                'pedidos_hoy'      => PedidoVenta::whereDate('fecha_pedido', $hoy)->count(),
                'ventas_mes'       => PedidoVenta::where('fecha_pedido', '>=', $inicioMes)->sum('total'),
                'pedidos_pendientes' => PedidoVenta::where('estado', 'activo')
                    ->where('estado_factura', 'pendiente')->count(),
                'compras_pendientes' => PedidoCompra::where('estado', 'pendiente')->count(),
            ];
        });

        $ventasUltimos30 = Cache::remember('ventas_30_dias', 900, function () {
            return PedidoVenta::selectRaw('DATE(fecha_pedido) as fecha, SUM(total) as total')
                ->where('fecha_pedido', '>=', Carbon::now()->subDays(29))
                ->groupBy('fecha')
                ->orderBy('fecha')
                ->get();
        });

        $ultimosPedidos = PedidoVenta::with(['cliente', 'usuario'])
            ->latest()
            ->take(10)
            ->get();

        $pedidosPorFacturar = PedidoVenta::with('cliente')
            ->where('estado', 'activo')
            ->where('estado_factura', 'pendiente')
            ->latest()
            ->take(5)
            ->get();

        $stockBajo = Producto::activos()
            ->withSum('movimientos', 'cantidad')
            ->stockBajo()
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'estadisticas', 'ventasUltimos30', 'ultimosPedidos',
            'pedidosPorFacturar', 'stockBajo'
        ));
    }
}
