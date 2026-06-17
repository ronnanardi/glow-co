<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Exports\SalesReportExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class SalesReportController extends Controller
{
    protected function getDateRange(Request $request): array
    {
        $start = $request->filled('start_date')
            ? \Carbon\Carbon::parse($request->start_date)
            : now()->subDays(29)->startOfDay();

        $end = $request->filled('end_date')
            ? \Carbon\Carbon::parse($request->end_date)
            : now()->endOfDay();

        return [$start, $end];
    }

    public function index(Request $request)
    {
        [$start, $end] = $this->getDateRange($request);

        $paidStatuses = ['paid', 'processed', 'shipped', 'completed'];

        // Ringkasan
        $totalRevenue = Order::whereIn('status', $paidStatuses)
            ->whereBetween('created_at', [$start, $end])
            ->sum('total_price');

        $totalOrders = Order::whereIn('status', $paidStatuses)
            ->whereBetween('created_at', [$start, $end])
            ->count();

        $avgOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        // Data grafik garis: penjualan per hari
        $dailySales = Order::whereIn('status', $paidStatuses)
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('DATE(created_at) as date, SUM(total_price) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $chartLabels = [];
        $chartData = [];
        $period = \Carbon\CarbonPeriod::create($start, $end);

        foreach ($period as $date) {
            $dateStr = $date->format('Y-m-d');
            $chartLabels[] = $date->format('d M');
            $match = $dailySales->firstWhere('date', $dateStr);
            $chartData[] = $match ? (float) $match->total : 0;
        }

        // Data grafik batang: penjualan per kategori
        $categorySales = OrderItem::join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('orders.status', $paidStatuses)
            ->whereBetween('orders.created_at', [$start, $end])
            ->selectRaw('categories.name as category, SUM(order_items.subtotal) as total')
            ->groupBy('categories.name')
            ->orderByDesc('total')
            ->get();

        // Produk terlaris
        $topProducts = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('orders.status', $paidStatuses)
            ->whereBetween('orders.created_at', [$start, $end])
            ->selectRaw('order_items.product_name, SUM(order_items.quantity) as total_sold, SUM(order_items.subtotal) as total_revenue')
            ->groupBy('order_items.product_name')
            ->orderByDesc('total_sold')
            ->take(10)
            ->get();

        return view('admin.sales-report.index', [
            'start'          => $start,
            'end'            => $end,
            'totalRevenue'   => $totalRevenue,
            'totalOrders'    => $totalOrders,
            'avgOrderValue'  => $avgOrderValue,
            'chartLabels'    => $chartLabels,
            'chartData'      => $chartData,
            'categorySales'  => $categorySales,
            'topProducts'    => $topProducts,
        ]);
    }

    public function exportExcel(Request $request)
    {
        [$start, $end] = $this->getDateRange($request);

        return Excel::download(new SalesReportExport($start, $end), 'laporan-penjualan-' . now()->format('Ymd') . '.xlsx');
    }

    public function exportPdf(Request $request)
    {
        [$start, $end] = $this->getDateRange($request);

        $paidStatuses = ['paid', 'processed', 'shipped', 'completed'];

        $orders = Order::with('user', 'items')
            ->whereIn('status', $paidStatuses)
            ->whereBetween('created_at', [$start, $end])
            ->orderBy('created_at')
            ->get();

        $totalRevenue = $orders->sum('total_price');

        $pdf = Pdf::loadView('admin.sales-report.pdf', [
            'orders'       => $orders,
            'start'        => $start,
            'end'          => $end,
            'totalRevenue' => $totalRevenue,
        ]);

        return $pdf->download('laporan-penjualan-' . now()->format('Ymd') . '.pdf');
    }
}