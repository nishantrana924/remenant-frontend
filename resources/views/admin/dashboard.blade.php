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
            <div class="flex items-center gap-2 px-4 py-2 bg-slate-50 rounded-2xl border border-slate-100">
                <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="text-[10px] font-black uppercase tracking-widest text-slate-600">Remenant Engine Active</span>
            </div>
            <button class="saas-btn-primary">
                <i data-lucide="download" class="w-4 h-4"></i>
                Export Insights
            </button>
        </div>
    </div>

    <!-- KPI Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Revenue -->
        <div class="saas-card group hover:border-orange-200 transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-2xl bg-orange-50 flex items-center justify-center text-orange-600 group-hover:scale-110 transition-transform">
                    <i data-lucide="banknote" class="w-6 h-6"></i>
                </div>
                <div class="text-right">
                    <span class="text-[10px] font-black text-emerald-500 bg-emerald-50 px-2.5 py-1 rounded-lg">+12.5%</span>
                </div>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Gross Revenue</p>
            <h3 class="text-3xl font-black text-slate-900 mt-1">₹{{ number_format($stats['total_revenue'] ?? 452800) }}</h3>
        </div>

        <!-- Orders -->
        <div class="saas-card group hover:border-blue-200 transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform">
                    <i data-lucide="shopping-cart" class="w-6 h-6"></i>
                </div>
                <div class="text-right">
                    <span class="text-[10px] font-black text-blue-500 bg-blue-50 px-2.5 py-1 rounded-lg">Orders</span>
                </div>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Total Transactions</p>
            <h3 class="text-3xl font-black text-slate-900 mt-1">{{ number_format($stats['total_orders'] ?? 1254) }}</h3>
        </div>

        <!-- Products -->
        <div class="saas-card group hover:border-indigo-200 transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600 group-hover:scale-110 transition-transform">
                    <i data-lucide="package" class="w-6 h-6"></i>
                </div>
                <div class="text-right">
                    <span class="text-[10px] font-black text-indigo-500 bg-indigo-50 px-2.5 py-1 rounded-lg">Active</span>
                </div>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Total Products</p>
            <h3 class="text-3xl font-black text-slate-900 mt-1">{{ $stats['total_products'] ?? 42 }}</h3>
        </div>

        <!-- Inventory Alerts -->
        <div class="saas-card group hover:border-rose-200 transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-2xl bg-rose-50 flex items-center justify-center text-rose-600 group-hover:scale-110 transition-transform">
                    <i data-lucide="shield-alert" class="w-6 h-6"></i>
                </div>
                <div class="text-right">
                    <span class="text-[10px] font-black text-rose-500 bg-rose-50 px-2.5 py-1 rounded-lg">Low Stock</span>
                </div>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Warehouse Alerts</p>
            <h3 class="text-3xl font-black text-slate-900 mt-1">{{ $stats['low_stock'] ?? 12 }}</h3>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Revenue Growth -->
        <div class="lg:col-span-2 saas-card p-8">
            <div class="flex items-center justify-between mb-10">
                <div>
                    <h3 class="text-base font-black text-slate-900 uppercase tracking-widest">Revenue Growth</h3>
                    <p class="text-xs text-slate-400 mt-1 font-medium">Monthly performance analysis</p>
                </div>
                <div class="flex items-center gap-6">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-orange-500 shadow-lg shadow-orange-200"></span>
                        <span class="text-[10px] font-black text-slate-600 uppercase tracking-widest">Current Year</span>
                    </div>
                    <div class="flex items-center gap-2 opacity-30">
                        <span class="w-3 h-3 rounded-full bg-slate-300"></span>
                        <span class="text-[10px] font-black text-slate-600 uppercase tracking-widest">Target</span>
                    </div>
                </div>
            </div>
            <div class="h-[350px] relative">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Recent Activity Feed -->
        <div class="saas-card">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-base font-black text-slate-900 uppercase tracking-widest">Recent Activity</h3>
                <i data-lucide="history" class="w-4 h-4 text-slate-300"></i>
            </div>
            <div class="space-y-6">
                @foreach($recent_orders ?? [] as $order)
                <div class="flex items-start gap-4 p-3 rounded-2xl hover:bg-slate-50 transition-all cursor-pointer group">
                    <div class="w-10 h-10 rounded-xl bg-white shadow-sm border border-slate-100 flex items-center justify-center text-slate-400 group-hover:text-orange-500 transition-colors">
                        <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-bold text-slate-900">#{{ $order->id }}</p>
                            <span class="text-[10px] font-black text-emerald-500 uppercase">PAID</span>
                        </div>
                        <p class="text-xs text-slate-400 truncate mt-0.5">{{ $order->user->name ?? 'Guest Customer' }}</p>
                        <p class="text-[9px] text-slate-300 font-bold mt-1 tracking-wider">{{ $order->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @endforeach
                
                @if(count($recent_orders ?? []) == 0)
                <div class="text-center py-16">
                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="package-search" class="w-8 h-8 text-slate-200"></i>
                    </div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">No Recent Orders</p>
                </div>
                @endif
            </div>
            <a href="{{ route('admin.orders.index') }}" class="block w-full mt-8 py-4 bg-slate-50 rounded-2xl text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-center hover:bg-orange-500 hover:text-white transition-all shadow-sm">Audit All Records</a>
        </div>
    </div>

    <!-- Quick Operations -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="saas-card overflow-hidden">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-base font-black text-slate-900 uppercase tracking-widest">Recent Users</h3>
                <a href="{{ route('admin.customers.index') }}" class="text-[10px] font-black text-orange-500 uppercase tracking-widest hover:underline">View All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="saas-table">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Identity</th>
                            <th class="text-right">Onboarded</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recent_users ?? [] as $user)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="h-8 w-8 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 font-black text-xs">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <span class="font-bold text-slate-900 text-sm">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="text-xs text-slate-400">{{ $user->email }}</span>
                            </td>
                            <td class="text-right">
                                <span class="text-[10px] font-black text-slate-400 uppercase">{{ $user->created_at->format('d M, Y') }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('revenueChart').getContext('2d');
    
    // Create Gradient
    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(255, 107, 0, 0.2)');
    gradient.addColorStop(1, 'rgba(255, 107, 0, 0.0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Revenue',
                data: [32000, 48000, 42000, 65000, 59000, 82000, 78000, 95000, 88000, 112000, 105000, 128000],
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
});
</script>
@endsection
