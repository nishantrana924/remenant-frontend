<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    public function index()
    {
        $items = Order::whereIn('status', ['processing', 'packed', 'shipped', 'out_for_delivery'])
            ->latest()
            ->paginate(15);
            
        $stats = [
            'to_ship' => Order::where('status', 'processing')->count(),
            'to_pack' => Order::where('status', 'packed')->count(),
            'in_transit' => Order::where('status', 'shipped')->count(),
            'out_for_delivery' => Order::where('status', 'out_for_delivery')->count(),
        ];

        return view('admin.shipping.index', compact('items', 'stats'));
    }
}
