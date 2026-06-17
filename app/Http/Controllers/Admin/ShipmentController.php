<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Shipment;
use Illuminate\Http\Request;

class ShipmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'shipment'])
            ->whereIn('status', ['processed', 'shipped']);

        // Filter status pengiriman
        if ($request->filled('status')) {
            if ($request->status === 'belum_resi') {
                $query->where('status', 'processed');
            } elseif ($request->status === 'dikirim') {
                $query->where('status', 'shipped');
            }
        }

        // Filter kurir
        if ($request->filled('courier')) {
            $query->where('courier', $request->courier);
        }

        // Search no resi / order number
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('shipment', function ($q2) use ($request) {
                      $q2->where('tracking_number', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $shipments = $query->latest()->paginate(15)->withQueryString();

        // Ringkasan
        $needResiCount = Order::where('status', 'processed')->count();
        $onDeliveryCount = Order::where('status', 'shipped')->count();

        return view('admin.shipments.index', compact('shipments', 'needResiCount', 'onDeliveryCount'));
    }
}