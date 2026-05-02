@extends('admin.layouts.app')

@section('header')
    <div class="flex items-center justify-between w-full pr-4">
        <div class="flex items-center gap-8">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 text-slate-400 hover:text-orange-500 transition-all font-bold text-sm group">
                <div class="h-8 w-8 rounded-full bg-slate-50 flex items-center justify-center group-hover:bg-orange-50 transition-colors">
                    <i data-lucide="arrow-left" class="h-4 w-4"></i>
                </div>
                Back
            </a>
            <div class="h-10 w-px bg-slate-100 hidden md:block"></div>
            <div class="flex flex-col justify-center">
                <h2 class="font-black text-xl text-slate-800 leading-none tracking-tight">Customer Orders</h2>
                <div class="flex items-center gap-2 mt-1.5">
                    <span class="h-1 w-1 bg-blue-500 rounded-full animate-pulse"></span>
                    <p class="text-[9px] text-slate-400 uppercase tracking-[0.2em] font-black">Fulfillment & Sales Tracking</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="page-enter space-y-8">
        <!-- Stats Summary -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="premium-card p-6 border-b-4 border-blue-500">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-500">
                        <i data-lucide="clock" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Pending Orders</p>
                        <h4 class="text-xl font-bold text-gray-800">{{ $items->where('status', 'pending')->count() }}</h4>
                    </div>
                </div>
            </div>
            <div class="premium-card p-6 border-b-4 border-orange-500">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-orange-50 flex items-center justify-center text-orange-500">
                        <i data-lucide="package" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Processing</p>
                        <h4 class="text-xl font-bold text-gray-800">{{ $items->where('status', 'processing')->count() }}</h4>
                    </div>
                </div>
            </div>
            <div class="premium-card p-6 border-b-4 border-green-500">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-green-50 flex items-center justify-center text-green-500">
                        <i data-lucide="check-circle" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Completed</p>
                        <h4 class="text-xl font-bold text-gray-800">{{ $items->where('status', 'completed')->count() }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="premium-card overflow-hidden">
            <div class="p-6 border-b border-gray-50 flex flex-wrap items-center justify-between gap-4">
                <div class="relative flex-1 min-w-[300px]">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                        <i data-lucide="search" class="h-5 w-5"></i>
                    </span>
                    <input type="text" placeholder="Search orders by ID or customer..." class="premium-input block w-full pl-12 pr-4 py-2 text-sm">
                </div>
                <div class="flex gap-2">
                    <button class="px-4 py-2 bg-gray-50 text-gray-600 rounded-xl text-xs font-bold hover:bg-gray-100 transition flex items-center gap-2">
                        <i data-lucide="filter" class="w-4 h-4"></i>
                        Filter
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50/50 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                            <th class="px-8 py-5">Order Details</th>
                            <th class="px-6 py-5">Customer</th>
                            <th class="px-6 py-5">Amount</th>
                            <th class="px-6 py-5">Status</th>
                            <th class="px-6 py-5">Payment</th>
                            <th class="px-8 py-5 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($items ?? [] as $item)
                            <tr class="hover:bg-gray-50/50 transition-all group">
                                <td class="px-8 py-5">
                                    <div class="text-sm font-bold text-gray-800 group-hover:text-[#ea5f06] transition-colors">#{{ $item->id }}</div>
                                    <div class="text-[10px] text-gray-400 mt-1 uppercase font-bold tracking-tighter">{{ $item->created_at->format('d M, Y | h:i A') }}</div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-[10px] font-bold text-gray-600">
                                            {{ strtoupper(substr($item->user->name ?? 'G', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-gray-700">{{ $item->user->name ?? 'Guest Customer' }}</div>
                                            <div class="text-[10px] text-gray-400">{{ $item->phone ?? 'No phone' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="text-sm font-bold text-gray-800">₹{{ number_format($item->total_amount) }}</div>
                                    <div class="text-[10px] text-gray-400">{{ $item->orderItems->count() }} items</div>
                                </td>
                                <td class="px-6 py-5">
                                    @php
                                        $statusClasses = [
                                            'pending' => 'bg-yellow-100 text-yellow-700',
                                            'processing' => 'bg-blue-100 text-blue-700',
                                            'shipped' => 'bg-purple-100 text-purple-700',
                                            'completed' => 'bg-green-100 text-green-700',
                                            'cancelled' => 'bg-red-100 text-red-700',
                                        ];
                                        $class = $statusClasses[$item->status] ?? 'bg-gray-100 text-gray-700';
                                    @endphp
                                    <span class="px-3 py-1 inline-flex text-[9px] leading-5 font-bold rounded-full {{ $class }} uppercase tracking-widest">
                                        {{ $item->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="flex items-center gap-1.5 text-[10px] font-bold {{ $item->payment_status === 'paid' ? 'text-green-600' : 'text-orange-600' }}">
                                        <div class="w-1.5 h-1.5 rounded-full {{ $item->payment_status === 'paid' ? 'bg-green-500' : 'bg-orange-500' }}"></div>
                                        {{ strtoupper($item->payment_status) }}
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <a href="{{ route('admin.orders.show', $item->id) }}" class="premium-button px-4 py-2 text-xs">
                                        View Details
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-8 py-20 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                            <i data-lucide="shopping-cart" class="w-10 h-10 text-gray-300"></i>
                                        </div>
                                        <h3 class="font-bold text-gray-800 text-lg">No Orders Yet</h3>
                                        <p class="text-gray-400 text-sm max-w-xs mt-1">When customers start buying your products, their orders will appear here.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
