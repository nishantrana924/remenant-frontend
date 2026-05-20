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
        $redirect = $request->user()->isAdmin() ? route('admin.dashboard') : route('my-orders');

        // Break out of Unpoly's fragment swapping if we're switching between public and admin layouts
        if ($request->header('X-Up-Target')) {
            return response()->redirectTo($redirect)
                ->header('X-Up-Target', ':main') // Force full page reload
                ->header('X-Up-Location', $redirect);
        }

        return redirect()->to($redirect);
    }

    /**
     * Display the customer dashboard.
     */
    public function user(Request $request): mixed
    {
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        $orders = \App\Models\Order::where('user_id', $user->id)->latest()->get();
        $addresses = $user->addresses()->latest()->get();
        $activeTab = $request->get('tab', 'orders');
        return view('public.dashboard', compact('orders', 'user', 'activeTab', 'addresses'));
    }

    /**
     * Display the admin dashboard.
     * Only accessible to admin users (protected by admin middleware).
     */
    public function admin(Request $request): View
    {
        $stats = \Illuminate\Support\Facades\Cache::remember('admin_dashboard_stats', 600, function() {
            // 1. Calculate Low Stock Count
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

        // 2. Prepare Revenue Chart Data (Monthly)
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

        // 3. Top Selling Products
        $topProducts = \App\Models\OrderItem::select('product_id', \DB::raw('SUM(quantity) as total_sold'))
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->with('product')
            ->take(5)
            ->get();

        // 4. Revenue by Category
        $categoryRevenue = \App\Models\Category::all()->map(function($cat) {
            $revenue = \App\Models\OrderItem::whereHas('product.categories', function($q) use ($cat) {
                $q->where('categories.id', $cat->id);
            })->sum(\DB::raw('quantity * price'));

            return [
                'name' => $cat->name,
                'revenue' => $revenue
            ];
        })->sortByDesc('revenue')->take(5);

        // 5. Recent Activity
        $recent_orders = \App\Models\Order::with('user')->latest()->take(5)->get();
        $recent_logs = \App\Models\InventoryLog::with(['product', 'user'])->latest()->take(8)->get();

        return view('admin.dashboard', compact('stats', 'recent_orders', 'chartData', 'topProducts', 'categoryRevenue', 'recent_logs'));
    }
}
