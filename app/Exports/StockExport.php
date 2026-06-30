<?php
namespace App\Exports;

use App\Models\Producto;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StockExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    public function collection()
    {
        return Producto::with(["categoria", "unidad"])
            ->activos()
            ->withSum("movimientos", "cantidad")
            ->orderBy("nombre")
            ->get();
    }

    public function headings(): array
    {
        return ["Código", "Nombre", "Categoría", "Unidad", "Stock Actual", "Precio Compra", "Precio Venta", "Valor en Stock"];
    }

    public function map($producto): array
    {
        $stock = $producto->movimientos_sum_cantidad ?? 0;
        return [
            $producto->codigo,
            $producto->nombre,
            $producto->categoria->nombre ?? "—",
            $producto->unidad->nombre ?? "—",
            number_format($stock, 2, ".", ""),
            number_format($producto->precio_compra, 2, ".", ""),
            number_format($producto->precio_venta_minorista, 2, ".", ""),
            number_format($stock * $producto->precio_compra, 2, ".", ""),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [1 => ["font" => ["bold" => true]]];
    }
}
