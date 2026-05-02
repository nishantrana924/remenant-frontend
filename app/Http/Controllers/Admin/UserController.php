<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $items = User::where('role', '!=', 'admin')->latest()->get();
        return view('admin.customers.index', compact('items'));
    }

    public function show($id)
    {
        $item = User::with(['orders.orderItems.product'])->findOrFail($id);
        
        $stats = [
            'total_spent' => $item->orders()->where('payment_status', 'paid')->sum('total_amount'),
            'total_orders' => $item->orders()->count(),
            'avg_order_value' => $item->orders()->count() > 0 ? $item->orders()->avg('total_amount') : 0,
        ];

        return view('admin.customers.show', compact('item', 'stats'));
    }
}
