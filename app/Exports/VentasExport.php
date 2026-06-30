<?php
namespace App\Exports;

use App\Models\PedidoVenta;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class VentasExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    private $desde;
    private $hasta;

    public function __construct($desde = null, $hasta = null)
    {
        $this->desde = $desde;
        $this->hasta = $hasta;
    }

    public function collection()
    {
        $query = PedidoVenta::with("cliente");

        if ($this->desde) {
            $query->whereDate("fecha_pedido", ">=", $this->desde);
        }
        if ($this->hasta) {
            $query->whereDate("fecha_pedido", "<=", $this->hasta);
        }

        return $query->latest("fecha_pedido")->get();
    }

    public function headings(): array
    {
        return ["N° Referencia", "Cliente", "Fecha", "Total", "Pagado", "Saldo", "Estado", "Estado Factura"];
    }

    public function map($pedido): array
    {
        return [
            $pedido->numero_referencia,
            $pedido->cliente->nombre ?? "—",
            $pedido->fecha_pedido->format("d/m/Y"),
            number_format($pedido->total, 2, ".", ""),
            number_format($pedido->monto_pagado, 2, ".", ""),
            number_format($pedido->total - $pedido->monto_pagado, 2, ".", ""),
            ucfirst($pedido->estado),
            ucfirst($pedido->estado_factura),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [1 => ["font" => ["bold" => true]]];
    }
}
