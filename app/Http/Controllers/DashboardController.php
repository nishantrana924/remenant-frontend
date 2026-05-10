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
        $stats = \Illuminate\Support\Facades\Cache::remember('admin_dashboard_stats', 600, function() {
            // 1. Calculate Low Stock Count (Products without variants + All variants)
            $lowStockProducts = \App\Models\Product::doesntHave('variants')->where('stock', '<', 10)->count();
            $lowStockVariants = \App\Models\ProductVariant::where('stock', '<', 10)->count();
            $lowStockCount = $lowStockProducts + $lowStockVariants;

            return [
                'total_users' => \App\Models\User::count(),
                'total_products' => \App\Models\Product::count(),
                'total_orders' => \App\Models\Order::count(),
                'total_revenue' => \App\Models\Order::where('payment_status', 'paid')->sum('total_amount'),
                'low_stock' => $lowStockCount,
            ];
        });

        // 2. Prepare Revenue Chart Data (Monthly for current year)
        $chartData = \Illuminate\Support\Facades\Cache::remember('admin_dashboard_chart_'.date('Y'), 3600, function() {
            $monthlyRevenue = \App\Models\Order::where('payment_status', 'paid')
                ->whereYear('created_at', date('Y'))
                ->selectRaw('MONTH(created_at) as month, SUM(total_amount) as amount')
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('amount', 'month')
                ->toArray();

            $data = [];
            for ($m = 1; $m <= 12; $m++) {
                $data[] = (float)($monthlyRevenue[$m] ?? 0);
            }
            return $data;
        });

        // Recent items are usually fresh, so we fetch them directly or with a very short cache
        $recent_orders = \App\Models\Order::with('user')->latest()->take(5)->get();
        $recent_users = \App\Models\User::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recent_orders', 'recent_users', 'chartData'));
    }
}
