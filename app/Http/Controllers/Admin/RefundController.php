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
            
        $stats = [
            'pending_refunds' => Order::whereIn('refund_status', ['pending', 'initiated', 'processing'])->count(),
            'total_refunded' => Order::where('refund_status', 'completed')->sum('refund_amount'),
        ];

        return view('admin.refunds.index', compact('items', 'stats'));
    }
}
