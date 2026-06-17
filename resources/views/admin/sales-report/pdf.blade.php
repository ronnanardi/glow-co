<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; color: #333; }
        h2 { margin-bottom: 0; color: #9A7B67; }
        .period { color: #777; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 6px 8px; text-align: left; }
        th { background: #f5f1ee; }
        .text-right { text-align: right; }
        .summary { margin: 15px 0; }
        .summary td { border: none; padding: 4px 8px; }
        .total-row { font-weight: bold; background: #f9f6f4; }
    </style>
</head>
<body>

    <h2>Laporan Penjualan - GLOW&CO</h2>
    <div class="period">
        Periode: {{ $start->format('d M Y') }} - {{ $end->format('d M Y') }}
    </div>

    <table class="summary">
        <tr>
            <td><strong>Total Pendapatan</strong></td>
            <td>: Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td><strong>Total Order</strong></td>
            <td>: {{ $orders->count() }} pesanan</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>No. Order</th>
                <th>Tanggal</th>
                <th>Customer</th>
                <th>Item</th>
                <th class="text-right">Total</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
                <tr>
                    <td>{{ $order->order_number }}</td>
                    <td>{{ $order->created_at->format('d-m-Y') }}</td>
                    <td>{{ $order->user->name }}</td>
                    <td>{{ $order->items->sum('quantity') }}</td>
                    <td class="text-right">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                    <td>{{ ucfirst($order->status) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="4" class="text-right">Total</td>
                <td class="text-right">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

</body>
</html>