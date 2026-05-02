@extends('admin.layouts.app')

@section('content')
<div class="space-y-8 pb-12">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Intelligence Overview</h1>
            <p class="text-sm text-slate-500 mt-1">Real-time performance metrics and business health</p>
        </div>
        <div class="flex items-center gap-3">
            <button class="saas-btn-secondary flex items-center gap-2">
                <i data-lucide="calendar" class="w-4 h-4"></i>
                Last 30 Days
            </button>
            <button class="saas-btn-primary">
                <i data-lucide="download" class="w-4 h-4"></i>
                Export Report
            </button>
        </div>
    </div>

    <!-- KPI Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="saas-card">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 rounded-lg bg-orange-50 flex items-center justify-center text-orange-600">
                    <i data-lucide="banknote" class="w-5 h-5"></i>
                </div>
                <span class="text-[10px] font-bold text-emerald-500 bg-emerald-50 px-2 py-1 rounded-md">+12.5%</span>
            </div>
            <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Gross Revenue</p>
            <h3 class="text-2xl font-bold text-slate-900 mt-1">₹{{ number_format($stats['revenue'] ?? 452800) }}</h3>
        </div>

        <div class="saas-card">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600">
                    <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                </div>
                <span class="text-[10px] font-bold text-blue-500 bg-blue-50 px-2 py-1 rounded-md">+8.2%</span>
            </div>
            <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Total Orders</p>
            <h3 class="text-2xl font-bold text-slate-900 mt-1">{{ $stats['orders_count'] ?? 1254 }}</h3>
        </div>

        <div class="saas-card">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600">
                    <i data-lucide="users" class="w-5 h-5"></i>
                </div>
                <span class="text-[10px] font-bold text-emerald-500 bg-emerald-50 px-2 py-1 rounded-md">+24%</span>
            </div>
            <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">New Customers</p>
            <h3 class="text-2xl font-bold text-slate-900 mt-1">{{ $stats['customers_count'] ?? 842 }}</h3>
        </div>

        <div class="saas-card">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 rounded-lg bg-rose-50 flex items-center justify-center text-rose-600">
                    <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                </div>
                <span class="text-[10px] font-bold text-rose-500 bg-rose-50 px-2 py-1 rounded-md">Low Stock</span>
            </div>
            <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Inventory Alerts</p>
            <h3 class="text-2xl font-bold text-slate-900 mt-1">{{ $stats['low_stock'] ?? 12 }}</h3>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Revenue Chart -->
        <div class="lg:col-span-2 saas-card">
            <div class="flex items-center justify-between mb-8">
                <h3 class="font-bold text-slate-900">Revenue Growth</h3>
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-orange-500"></span>
                        <span class="text-xs text-slate-500">Current Year</span>
                    </div>
                </div>
            </div>
            <canvas id="revenueChart" height="300"></canvas>
        </div>

        <!-- Recent Activity -->
        <div class="saas-card">
            <h3 class="font-bold text-slate-900 mb-6">Recent Activity</h3>
            <div class="space-y-6">
                @foreach($recent_orders ?? [] as $order)
                <div class="flex items-start gap-4">
                    <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 flex-shrink-0">
                        <i data-lucide="package" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-800">Order #{{ $order->id }}</p>
                        <p class="text-xs text-slate-500">{{ $order->customer_name }} • ₹{{ number_format($order->total_amount) }}</p>
                        <p class="text-[10px] text-slate-400 mt-1">{{ $order->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @endforeach
                
                <!-- Placeholder if empty -->
                @if(count($recent_orders ?? []) == 0)
                <div class="text-center py-12">
                    <i data-lucide="inbox" class="w-12 h-12 text-slate-200 mx-auto mb-4"></i>
                    <p class="text-sm text-slate-400">No recent activity found</p>
                </div>
                @endif
            </div>
            <button class="w-full mt-8 py-3 text-xs font-bold text-slate-400 uppercase tracking-widest hover:text-orange-500 transition-colors border-t border-slate-50">View All Orders</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Revenue',
                data: [32000, 45000, 42000, 58000, 65000, 59000, 72000, 81000, 78000, 92000, 105000, 118000],
                borderColor: '#F97316',
                backgroundColor: 'rgba(249, 115, 22, 0.05)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { borderDash: [5, 5], drawBorder: false } },
                x: { grid: { display: false } }
            }
        }
    });
});
</script>
@endsection
