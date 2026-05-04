<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $items = User::with('orders')->latest()->get();
        return view('admin.customers.index', compact('items'));
    }

    public function updateRole(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->role_id = (int)$request->role;
        $user->save();

        return redirect()->back()->with('success', 'User role updated successfully.');
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
