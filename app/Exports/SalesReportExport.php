<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Font;

class SalesReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function __construct(protected $start, protected $end) {}

    public function collection()
    {
        return Order::with('user', 'items')
            ->whereIn('status', ['paid', 'processed', 'shipped', 'completed'])
            ->whereBetween('created_at', [$this->start, $this->end])
            ->orderBy('created_at')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No. Order', 'Tanggal', 'Customer', 'Jumlah Produk',
            'Subtotal', 'Ongkir', 'Diskon', 'Total', 'Status'
        ];
    }

    public function map($order): array
    {
        return [
            $order->order_number,
            $order->created_at->format('d-m-Y H:i'),
            $order->user->name,
            $order->items->sum('quantity'),
            $order->total_price - $order->shipping_cost + $order->discount,
            $order->shipping_cost,
            $order->discount,
            $order->total_price,
            ucfirst($order->status),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}