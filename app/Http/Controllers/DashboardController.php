<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     * All authenticated users are admins and redirected to admin dashboard.
     */
    public function index(Request $request): RedirectResponse
    {
        return redirect()->route('admin.dashboard');
    }

    /**
     * Display the admin dashboard.
     * Only accessible to admin users (protected by admin middleware).
     */
    public function admin(Request $request): View
    {
        $stats = [
            'total_users' => \App\Models\User::count(),
            'total_products' => \App\Models\Product::count(),
            'total_orders' => \App\Models\Order::count(),
            'total_revenue' => \App\Models\Order::where('payment_status', 'paid')->sum('total_amount'),
        ];

        $recent_orders = \App\Models\Order::with('user')->latest()->take(5)->get();
        $recent_users = \App\Models\User::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recent_orders', 'recent_users'));
    }
}
