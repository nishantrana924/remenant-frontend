<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     * Redirects to admin or user dashboard based on role.
     */
    public function index(Request $request): RedirectResponse
    {
        if ($request->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('my-orders');
    }

    /**
     * Display the customer dashboard.
     */
    public function user(Request $request): View
    {
        $user = auth()->user();
        $orders = \App\Models\Order::where('user_id', $user->id)->latest()->get();
        return view('public.dashboard', compact('orders', 'user'));
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
