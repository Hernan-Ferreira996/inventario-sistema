<?php
namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\PedidoVenta;
use App\Models\PedidoCompra;
use App\Exports\StockExport;
use App\Exports\VentasExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteController extends Controller
{
    public function stock()
    {
        $productos = Producto::activos()->with(["categoria", "unidad"])
            ->withSum("movimientos", "cantidad")->orderBy("nombre")->get();
        return view("reportes.stock", compact("productos"));
    }

    public function ventas(Request $request)
    {
        $desde = $request->desde ?? now()->startOfMonth()->toDateString();
        $hasta = $request->hasta ?? now()->toDateString();
        $pedidos = PedidoVenta::with("cliente")->whereBetween("fecha_pedido", [$desde, $hasta])->get();
        $total = $pedidos->sum("total");
        return view("reportes.ventas", compact("pedidos", "total", "desde", "hasta"));
    }

    public function compras(Request $request)
    {
        $desde = $request->desde ?? now()->startOfMonth()->toDateString();
        $hasta = $request->hasta ?? now()->toDateString();
        $pedidos = PedidoCompra::with("proveedor")->whereBetween("fecha_pedido", [$desde, $hasta])->get();
        $total = $pedidos->sum("total");
        return view("reportes.compras", compact("pedidos", "total", "desde", "hasta"));
    }

    public function stockExcel()
    {
        return Excel::download(new StockExport, "reporte-stock-" . now()->format("Y-m-d") . ".xlsx");
    }

    public function ventasExcel(Request $request)
    {
        $desde = $request->desde ?? now()->startOfMonth()->toDateString();
        $hasta = $request->hasta ?? now()->toDateString();
        return Excel::download(new VentasExport($desde, $hasta), "reporte-ventas-" . now()->format("Y-m-d") . ".xlsx");
    }

    public function stockPdf()
    {
        $productos = Producto::activos()->with(["categoria", "unidad"])
            ->withSum("movimientos", "cantidad")->orderBy("nombre")->get();
        $pdf = Pdf::loadView("reportes.stock-pdf", compact("productos"))->setPaper("a4", "landscape");
        return $pdf->stream("reporte-stock-" . now()->format("Y-m-d") . ".pdf");
    }

    public function ventasPdf(Request $request)
    {
        $desde = $request->desde ?? now()->startOfMonth()->toDateString();
        $hasta = $request->hasta ?? now()->toDateString();
        $pedidos = PedidoVenta::with("cliente")->whereBetween("fecha_pedido", [$desde, $hasta])->get();
        $total = $pedidos->sum("total");
        $pdf = Pdf::loadView("reportes.ventas-pdf", compact("pedidos", "total", "desde", "hasta"))->setPaper("a4", "landscape");
        return $pdf->stream("reporte-ventas-" . now()->format("Y-m-d") . ".pdf");
    }
}
