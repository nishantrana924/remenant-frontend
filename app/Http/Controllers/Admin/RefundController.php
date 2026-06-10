<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class RefundController extends Controller
{
    public function index()
    {
        $items = Order::where('refund_status', '!=', 'none')
            ->latest()
            ->paginate(15);

        $returnRequests = Order::where('return_status', '!=', 'none')
            ->whereNotNull('return_status')
            ->latest()
            ->get();
            
        $stats = [
            'pending_refunds' => Order::whereIn('refund_status', ['pending', 'initiated', 'processing'])->count(),
            'total_refunded' => Order::where('refund_status', 'completed')->sum('refund_amount'),
            'pending_returns' => Order::where('return_status', 'requested')->count(),
            'return_shipping_collected' => Order::where('return_status', 'approved')->sum('return_shipping_charge'),
        ];

        return view('admin.refunds.index', compact('items', 'stats', 'returnRequests'));
    }
}
