@extends('admin.layouts.app')

@section('header')
    <h2 class="font-bold text-xl text-slate-900 leading-tight">Control Center</h2>
@endsection

@section('content')
<div class="space-y-8 pb-12">
    <!-- Systematic Welcome Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight uppercase">Overview</h1>
            <div class="flex items-center gap-2 mt-1">
                <span class="h-1.5 w-1.5 rounded-full bg-orange-500"></span>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Business Health • Real-time Sync</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <div class="hidden sm:flex items-center gap-2 px-4 py-2 bg-white rounded-xl border border-slate-100 shadow-sm">
                <div class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></div>
                <span class="text-[9px] font-bold uppercase tracking-widest text-slate-600">System Live</span>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="saas-btn-secondary py-2.5">
                <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                Timeline
            </a>
            <a href="{{ route('admin.products.create') }}" class="saas-btn-primary py-2.5 px-6">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                Create New
            </a>
        </div>
    </div>

    <!-- Metric Pulse Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Revenue pulse -->
        <div class="saas-card group relative overflow-hidden bg-orange-600 border-0 shadow-2xl shadow-orange-100">
            <div class="absolute -right-4 -top-4 opacity-10 group-hover:scale-110 transition-transform duration-700">
                <i data-lucide="banknote" class="w-24 h-24 text-white"></i>
            </div>
            <div class="relative z-10">
                <p class="text-[9px] font-bold text-orange-100 uppercase tracking-[0.2em] mb-4">Total Revenue</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-3xl font-bold text-white tracking-tighter">₹{{ number_format($stats['total_revenue'] ?? 452800) }}</h3>
                    <span class="text-[9px] font-bold text-white/80">+12%</span>
                </div>
            </div>
        </div>

        <div class="saas-card group hover:border-orange-500 transition-all duration-300">
            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-4">Total Orders</p>
            <div class="flex items-baseline justify-between">
                <h3 class="text-3xl font-bold text-slate-900 tracking-tighter">{{ number_format($stats['total_orders'] ?? 1254) }}</h3>
                <div class="h-10 w-10 rounded-xl bg-orange-50 flex items-center justify-center text-orange-500 group-hover:bg-orange-500 group-hover:text-white transition-all">
                    <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                </div>
            </div>
        </div>

        <div class="saas-card group hover:border-blue-500 transition-all duration-300">
            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-4">Products</p>
            <div class="flex items-baseline justify-between">
                <h3 class="text-3xl font-bold text-slate-900 tracking-tighter">{{ $stats['total_products'] ?? 42 }}</h3>
                <div class="h-10 w-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-500 group-hover:bg-blue-500 group-hover:text-white transition-all">
                    <i data-lucide="layers" class="w-5 h-5"></i>
                </div>
            </div>
        </div>

        <div class="saas-card group hover:border-rose-500 transition-all duration-300">
            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-4">Low Stock</p>
            <div class="flex items-baseline justify-between">
                <h3 class="text-3xl font-bold text-slate-900 tracking-tighter">{{ $stats['low_stock'] ?? 12 }}</h3>
                <div class="h-10 w-10 rounded-xl bg-rose-50 flex items-center justify-center text-rose-500 group-hover:bg-rose-500 group-hover:text-white transition-all">
                    <i data-lucide="shield-alert" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Core Intelligence Matrix -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Visual Data Flow -->
        <div class="lg:col-span-8 saas-card p-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-[10px] font-bold text-slate-900 uppercase tracking-[0.25em]">Revenue Stats</h3>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-1">Monthly performance analysis</p>
                </div>
                <div class="flex items-center gap-6">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-orange-500 shadow-lg shadow-orange-200"></span>
                        <span class="text-[9px] font-black text-slate-600 uppercase tracking-widest">Actual</span>
                    </div>
                </div>
            </div>
            <div class="h-[300px] sm:h-[350px] relative">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- High-Velocity Feed -->
        <div class="lg:col-span-4 saas-card flex flex-col">
            <div class="flex items-center justify-between mb-8 pb-4 border-b border-slate-50">
                <h3 class="text-[10px] font-bold text-slate-900 uppercase tracking-[0.25em]">Recent Orders</h3>
                <span class="h-6 w-6 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400">
                    <i data-lucide="zap" class="w-3.5 h-3.5"></i>
                </span>
            </div>
            <div class="flex-1 space-y-6">
                @foreach($recent_orders ?? [] as $order)
                <div class="flex items-start gap-4 p-3 rounded-2xl hover:bg-slate-50 transition-all cursor-pointer group border border-transparent hover:border-slate-100">
                    <div class="w-10 h-10 rounded-xl bg-white shadow-sm border border-slate-100 flex items-center justify-center text-slate-400 group-hover:bg-slate-900 group-hover:text-white transition-all">
                        <i data-lucide="package" class="w-5 h-5"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <p class="text-xs font-bold text-slate-900 uppercase">#{{ $order->order_number }}</p>
                            @php
                                $statusColors = [
                                    'paid' => 'bg-emerald-50 text-emerald-600',
                                    'unpaid' => 'bg-rose-50 text-rose-600',
                                    'pending' => 'bg-orange-50 text-orange-600',
                                ];
                                $color = $statusColors[$order->payment_status] ?? 'bg-slate-50 text-slate-400';
                            @endphp
                            <span class="text-[8px] font-black {{ $color }} px-2 py-0.5 rounded-full uppercase">{{ $order->payment_status }}</span>
                        </div>
                        <p class="text-[10px] font-bold text-slate-400 truncate mt-0.5 uppercase tracking-tighter">{{ $order->customer_name }}</p>
                        <p class="text-[8px] text-slate-300 font-black mt-1 uppercase">{{ $order->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @endforeach
                
                @if(count($recent_orders ?? []) == 0)
                <div class="text-center py-20">
                    <div class="w-16 h-16 bg-slate-50 rounded-[2rem] flex items-center justify-center mx-auto mb-4 border border-slate-100">
                        <i data-lucide="loader-2" class="w-8 h-8 text-slate-200 animate-spin"></i>
                    </div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Awaiting Transactions</p>
                </div>
                @endif
            </div>
            <a href="{{ route('admin.orders.index') }}" class="mt-8 py-3 bg-slate-50 rounded-xl text-[9px] font-bold text-slate-500 uppercase tracking-[0.2em] text-center hover:bg-orange-600 hover:text-white transition-all">View All Orders</a>
        </div>
    </div>

    <!-- Detailed Ledger Section -->
    <div class="saas-card overflow-hidden bg-white border border-slate-100 shadow-xl shadow-slate-200/40">
        <div class="p-6 border-b border-slate-50 flex items-center justify-between">
            <h3 class="text-[10px] font-bold text-slate-900 uppercase tracking-[0.25em]">Recent Customers</h3>
            <a href="{{ route('admin.customers.index') }}" class="text-[9px] font-bold text-orange-500 uppercase tracking-[0.2em] hover:brightness-110">View All</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[8px] font-bold uppercase tracking-[0.25em] text-slate-400 bg-slate-50/50">
                        <th class="px-6 py-4">Customer</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4 text-right">Joined</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($recent_users ?? [] as $user)
                    <tr class="hover:bg-slate-50/50 transition-all cursor-default">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 rounded-lg bg-orange-50 flex items-center justify-center text-orange-500 font-bold text-[10px] border border-orange-100">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <span class="font-bold text-slate-900 text-xs uppercase tracking-tight">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">{{ $user->email }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ $user->created_at->format('d M • Y') }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
(function() {
    var chartData = @json($chartData);

    function initRevenueChart() {
        var canvas = document.getElementById('revenueChart');
        if (!canvas) return;
        if (typeof Chart === 'undefined') return;

        // Destroy existing chart instance to prevent re-init errors
        var existing = Chart.getChart(canvas);
        if (existing) existing.destroy();

        var ctx = canvas.getContext('2d');
        var gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(255, 107, 0, 0.2)');
        gradient.addColorStop(1, 'rgba(255, 107, 0, 0.0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Revenue',
                    data: chartData,
                    borderColor: '#FF6B00',
                    backgroundColor: gradient,
                    borderWidth: 4,
                    fill: true,
                    tension: 0.45,
                    pointRadius: 4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#FF6B00',
                    pointBorderWidth: 2,
                    pointHoverRadius: 6,
                    pointHoverBackgroundColor: '#FF6B00',
                    pointHoverBorderColor: '#fff',
                    pointHoverBorderWidth: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { intersect: false, mode: 'index' },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleFont: { size: 12, weight: 'bold', family: 'Inter' },
                        bodyFont: { size: 14, weight: 'black', family: 'Inter' },
                        padding: 16,
                        cornerRadius: 12,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return '₹ ' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(241, 245, 249, 1)', drawBorder: false },
                        ticks: {
                            font: { size: 10, weight: 'bold', family: 'Inter' },
                            color: '#94a3b8',
                            callback: function(value) { return '₹' + (value/1000) + 'k'; }
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: { size: 10, weight: 'bold', family: 'Inter' },
                            color: '#94a3b8'
                        }
                    }
                }
            }
        });
    }

    // Init on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initRevenueChart);
    } else {
        initRevenueChart();
    }

    // Re-init when Unpoly swaps in this fragment
    if (window.up) {
        up.on('up:fragment:inserted', function(event) {
            if (event.fragment && event.fragment.querySelector('#revenueChart')) {
                initRevenueChart();
            }
        });
    }
})();
</script>
@endsection
