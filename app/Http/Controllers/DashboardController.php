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

        $ordersQuery = \App\Models\Order::where('user_id', $user->id);

        $orderStats = [
            'total' => (clone $ordersQuery)->count(),
            'active' => (clone $ordersQuery)->where(function($q) {
                $q->where('payment_status', 'paid');
            })->where(function($q) {
                $q->whereNull('delivery_status')->orWhere('delivery_status', '!=', 'Delivered');
            })->count(),
            'delivered' => (clone $ordersQuery)->where('delivery_status', 'Delivered')->count(),
            'pending' => (clone $ordersQuery)->where('payment_status', '!=', 'paid')->count(),
        ];

        $orders = (clone $ordersQuery)->whereNotIn('status', ['cancelled', 'cancellation_requested'])
            ->with(['orderItems.product:id,title,slug,image'])
            ->latest()
            ->paginate(10, ['*'], 'orders_page');

        $cancelledOrders = (clone $ordersQuery)->whereIn('status', ['cancelled', 'cancellation_requested'])
            ->with(['orderItems.product:id,title,slug,image'])
            ->latest()
            ->paginate(10, ['*'], 'cancelled_page');
        
        $addresses = $user->addresses()->latest()->get();
        
        $allowedTabs = ['orders', 'profile', 'addresses'];
        $activeTab = $request->get('tab', 'orders');
        
        if (!in_array($activeTab, $allowedTabs)) {
            $activeTab = 'orders';
        }

        return view('public.dashboard', compact('orders', 'cancelledOrders', 'orderStats', 'user', 'activeTab', 'addresses'));
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
        $categoryRevenue = \App\Models\Category::lazy()->map(function($cat) {
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

    /**
     * Cancel an order.
     */
    public function cancelOrder(Request $request, \App\Models\Order $order)
    {
        // 1. Validate reason
        $request->validate([
            'cancellation_reason' => 'required|string|max:500'
        ]);

        // 2. Authorization
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // 3. Database Transaction & Pessimistic Locking
        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($order, $request) {
                // Lock the order
                $order = \App\Models\Order::where('id', $order->id)->lockForUpdate()->first();

                // 4. Validate Status Matrix
                $blockedStatuses = ['shipped', 'out_for_delivery', 'delivered', 'returned', 'cancelled'];
                if (in_array(strtolower($order->delivery_status ?? ''), $blockedStatuses) || in_array(strtolower($order->status), $blockedStatuses)) {
                    throw new \Exception('This order cannot be cancelled in its current state.');
                }

                // Verify it's not already being refunded
                if (in_array($order->refund_status, ['processing', 'completed'])) {
                    throw new \Exception('Refund already initiated or completed.');
                }

                // Packed orders require admin review instead of direct cancellation
                if (strtolower($order->delivery_status ?? '') === 'packed') {
                    $order->update([
                        'status' => 'cancellation_requested',
                        'cancellation_reason' => $request->cancellation_reason,
                    ]);
                    $order->logStatus("Cancellation requested by customer. Reason: " . $request->cancellation_reason, auth()->id());
                    return; // Stop here, admin will review
                }

                // 5. Update Order Status
                $order->update([
                    'status' => 'cancelled',
                    'cancelled_at' => now(),
                    'cancelled_by' => 'customer',
                    'cancellation_reason' => $request->cancellation_reason,
                ]);

                if ($order->payment_status === 'paid') {
                    $order->update(['refund_status' => 'pending']);
                }

                // 6. Restore Inventory
                // REMOVED: Stock restoration is now handled natively and securely by OrderObserver@updated
                // to prevent ghost inventory creation.

                // 7. Log Timeline
                $order->logStatus("Order cancelled by customer. Reason: " . $request->cancellation_reason, auth()->id());
                if ($order->payment_status === 'paid') {
                    $order->logStatus("Refund initiated.", auth()->id());
                }
            });

            // 8. Dispatch Refund Job (Outside transaction to prevent API calls holding DB locks)
            $order->refresh();
            if ($order->payment_status === 'paid' && $order->status === 'cancelled') {
                \App\Jobs\ProcessOrderRefundJob::dispatch($order->id);
            }

            $message = $order->status === 'cancellation_requested' 
                ? 'Cancellation requested. An admin will review since the order is already packed.'
                : 'Order cancelled successfully.';

            return back()->with('success', $message);

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
