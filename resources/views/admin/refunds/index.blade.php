@extends('admin.layouts.app')

@section('content')
<div class="space-y-8 pb-24">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight uppercase">Refunds & Returns</h1>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Capital Protection • System Audit</p>
        </div>
    </div>

    <!-- Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="saas-card group hover:border-rose-500 transition-all">
            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-3">Pending Refund Requests</p>
            <div class="flex items-baseline justify-between">
                <h3 class="text-3xl font-bold text-slate-900 tracking-tighter">{{ $stats['pending_refunds'] }}</h3>
                <div class="h-10 w-10 rounded-xl bg-rose-50 flex items-center justify-center text-rose-500 group-hover:bg-rose-500 group-hover:text-white transition-all">
                    <i data-lucide="alert-circle" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
        <div class="saas-card group hover:border-amber-500 transition-all">
            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-3">Pending Return Requests</p>
            <div class="flex items-baseline justify-between">
                <h3 class="text-3xl font-bold text-slate-900 tracking-tighter">{{ $stats['pending_returns'] ?? 0 }}</h3>
                <div class="h-10 w-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-500 group-hover:bg-amber-500 group-hover:text-white transition-all">
                    <i data-lucide="package-x" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
        <div class="saas-card bg-orange-600 border-0">
            <p class="text-[9px] font-bold text-orange-100 uppercase tracking-widest mb-3">Total Amount Refunded</p>
            <div class="flex items-baseline justify-between">
                <h3 class="text-3xl font-bold text-white tracking-tighter">₹{{ number_format($stats['total_refunded']) }}</h3>
                <i data-lucide="rotate-ccw" class="w-5 h-5 text-orange-400"></i>
            </div>
        </div>
        <div class="saas-card group hover:border-blue-500 transition-all">
            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-3">Return Shipping Collected</p>
            <div class="flex items-baseline justify-between">
                <h3 class="text-3xl font-bold text-slate-900 tracking-tighter">₹{{ number_format($stats['return_shipping_collected'] ?? 0) }}</h3>
                <div class="h-10 w-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-500 group-hover:bg-blue-500 group-hover:text-white transition-all">
                    <i data-lucide="truck" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div x-data="{ tab: 'returns' }">
        <div class="flex gap-1 border-b border-slate-100 mb-6">
            <button @click="tab = 'returns'"
                :class="tab === 'returns' ? 'text-slate-900 border-b-2 border-orange-500' : 'text-slate-400 hover:text-slate-600'"
                class="px-6 py-3 text-xs font-bold uppercase tracking-widest transition-colors flex items-center gap-2">
                <i data-lucide="package-x" class="w-3.5 h-3.5"></i>
                Return Requests
                @if(($stats['pending_returns'] ?? 0) > 0)
                    <span class="bg-amber-500 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full">{{ $stats['pending_returns'] }}</span>
                @endif
            </button>
            <button @click="tab = 'refunds'"
                :class="tab === 'refunds' ? 'text-slate-900 border-b-2 border-orange-500' : 'text-slate-400 hover:text-slate-600'"
                class="px-6 py-3 text-xs font-bold uppercase tracking-widest transition-colors flex items-center gap-2">
                <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                Refund Records
            </button>
        </div>

        {{-- ─── Return Requests Tab ──────────────────────────────────────────── --}}
        <div x-show="tab === 'returns'" x-data="{ search: '' }">
            <div class="saas-card p-0 overflow-hidden border border-slate-100 shadow-xl shadow-slate-200/40">
                <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/20 flex items-center justify-between">
                    <h3 class="text-[10px] font-bold text-slate-900 uppercase tracking-[0.25em]">Return Requests</h3>
                    <div class="relative w-64">
                        <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400"></i>
                        <input type="text" x-model="search" placeholder="Search returns..." class="saas-input pl-10 py-1.5 text-[10px] uppercase font-bold tracking-widest">
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-[8px] font-bold uppercase tracking-[0.25em] text-slate-400 bg-slate-50/50">
                                <th class="px-8 py-4">Order ID</th>
                                <th class="px-8 py-4">Customer</th>
                                <th class="px-8 py-4">Order Amount</th>
                                <th class="px-8 py-4">Net Refund</th>
                                <th class="px-8 py-4">Return Reason</th>
                                <th class="px-8 py-4">Requested</th>
                                <th class="px-8 py-4">Status</th>
                                <th class="px-8 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($returnRequests as $item)
                            <tr x-show="!search || '{{ strtolower($item->order_number) }}'.includes(search.toLowerCase()) || '{{ strtolower($item->customer_name) }}'.includes(search.toLowerCase())"
                                class="hover:bg-slate-50/50 transition-all">
                                <td class="px-8 py-5 font-bold text-slate-900 text-xs uppercase">
                                    <a href="{{ route('admin.orders.show', $item->id) }}" class="hover:text-orange-600 transition-colors">#{{ $item->order_number }}</a>
                                </td>
                                <td class="px-8 py-5 text-xs font-bold text-slate-600 uppercase tracking-tighter">{{ $item->customer_name }}</td>
                                <td class="px-8 py-5 font-bold text-slate-900 text-sm">₹{{ number_format($item->total_amount) }}</td>
                                <td class="px-8 py-5 font-bold text-emerald-600 text-sm">
                                    ₹{{ number_format($item->total_amount - 100) }}
                                    <span class="block text-[9px] text-slate-400 font-normal">−₹100 shipping</span>
                                </td>
                                <td class="px-8 py-5 max-w-xs">
                                    <p class="text-xs text-slate-600 leading-relaxed line-clamp-2">{{ $item->return_reason ?? '—' }}</p>
                                </td>
                                <td class="px-8 py-5 text-xs text-slate-500">
                                    {{ $item->return_requested_at ? \Carbon\Carbon::parse($item->return_requested_at)->format('d M Y') : '—' }}
                                </td>
                                <td class="px-8 py-5">
                                    @php
                                        $rs = $item->return_status ?? 'none';
                                        $rsBadge = match($rs) {
                                            'requested' => 'bg-amber-50 text-amber-700 border border-amber-100',
                                            'approved'  => 'bg-emerald-50 text-emerald-700 border border-emerald-100',
                                            'rejected'  => 'bg-rose-50 text-rose-700 border border-rose-100',
                                            'completed' => 'bg-blue-50 text-blue-700 border border-blue-100',
                                            default     => 'bg-slate-50 text-slate-400 border border-slate-100',
                                        };
                                    @endphp
                                    <span class="px-2 py-0.5 rounded-full text-[8px] font-bold uppercase {{ $rsBadge }}">
                                        {{ ucfirst($rs) }}
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-right">
                                     @if($item->return_status === 'requested')
                                         <div class="flex items-center justify-end gap-2">
                                             <button onclick="approveReturn({{ $item->id }}, '{{ $item->order_number }}', '{{ $item->total_amount }}')"
                                                 class="saas-btn py-1.5 px-3 text-[9px] font-bold uppercase tracking-widest bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg">
                                                 ✓ Approve
                                             </button>
                                            <button onclick="rejectReturn({{ $item->id }}, '{{ $item->order_number }}')"
                                                class="saas-btn-secondary py-1.5 px-3 text-[9px] font-bold uppercase tracking-widest text-rose-600 hover:text-rose-700 border-rose-200 hover:border-rose-300">
                                                ✗ Reject
                                            </button>
                                        </div>
                                    @elseif($item->return_status === 'approved')
                                        <div class="text-right">
                                            <span class="text-[9px] font-bold text-emerald-600">APPROVED</span>
                                            @if($item->return_awb)
                                                <p class="text-[9px] text-slate-400 mt-0.5">AWB: {{ $item->return_awb }}</p>
                                            @endif
                                        </div>
                                    @else
                                        <a href="{{ route('admin.orders.show', $item->id) }}" class="saas-btn-secondary py-1.5 px-3 text-[9px] font-bold uppercase tracking-widest">View</a>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="px-8 py-20 text-center">
                                    <p class="text-xs font-bold text-slate-300 uppercase tracking-[0.2em]">No return requests</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ─── Refund Records Tab ────────────────────────────────────────────── --}}
        <div x-show="tab === 'refunds'" x-data="{ search: '' }">
            <div class="saas-card p-0 overflow-hidden border border-slate-100 shadow-xl shadow-slate-200/40">
                <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/20 flex items-center justify-between">
                    <h3 class="text-[10px] font-bold text-slate-900 uppercase tracking-[0.25em]">Refund Records</h3>
                    <div class="relative w-64">
                        <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400"></i>
                        <input type="text" x-model="search" placeholder="Search refunds..." class="saas-input pl-10 py-1.5 text-[10px] uppercase font-bold tracking-widest">
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-[8px] font-bold uppercase tracking-[0.25em] text-slate-400 bg-slate-50/50">
                                <th class="px-8 py-4">Order ID</th>
                                <th class="px-8 py-4">Customer</th>
                                <th class="px-8 py-4">Amount</th>
                                <th class="px-8 py-4">Status</th>
                                <th class="px-8 py-4 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($items as $item)
                            <tr x-show="!search || '{{ strtolower($item->order_number) }}'.includes(search.toLowerCase()) || '{{ strtolower($item->customer_name) }}'.includes(search.toLowerCase())"
                                class="hover:bg-slate-50/50 transition-all">
                                <td class="px-8 py-6 font-bold text-slate-900 text-xs uppercase">#{{ $item->order_number }}</td>
                                <td class="px-8 py-6 text-xs font-bold text-slate-600 uppercase tracking-tighter">{{ $item->customer_name }}</td>
                                <td class="px-8 py-6 font-bold text-rose-600 text-sm tracking-tighter">₹{{ number_format($item->refund_amount) }}</td>
                                <td class="px-8 py-6">
                                    <span class="px-2 py-0.5 rounded-full text-[8px] font-bold uppercase bg-slate-50 text-slate-400 border border-slate-100">{{ $item->refund_status }}</span>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <a href="{{ route('admin.orders.show', $item->id) }}" class="saas-btn-secondary py-1.5 px-3 text-[9px] font-bold uppercase tracking-widest">Resolve</a>
                                </td>
                            </tr>
                            @endforeach

                            @if($items->count() == 0)
                            <tr>
                                <td colspan="5" class="px-8 py-20 text-center">
                                    <p class="text-xs font-bold text-slate-300 uppercase tracking-[0.2em]">No refunds on record</p>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Reject Return Modal --}}
<div id="reject-return-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-slate-900">Reject Return — <span id="reject-order-number" class="text-rose-600"></span></h3>
            <button onclick="closeRejectModal()" class="text-slate-400 hover:text-slate-600"><i data-lucide="x" class="h-5 w-5"></i></button>
        </div>
        <form id="reject-return-form" method="POST" action="">
            @csrf
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Rejection Reason <span class="text-rose-500">*</span></label>
                    <textarea name="admin_note" rows="3" required maxlength="500"
                        placeholder="Why is this return being rejected? (e.g. Outside return window, product is consumable, etc.)"
                        class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-rose-500 focus:border-rose-500 resize-none"></textarea>
                </div>
            </div>
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3">
                <button type="button" onclick="closeRejectModal()" class="px-5 py-2.5 text-sm font-medium text-slate-600 hover:text-slate-900">Go Back</button>
                <button type="submit" class="px-6 py-2.5 bg-rose-600 text-white text-sm font-semibold rounded-xl hover:bg-rose-700 transition-colors">Confirm Rejection</button>
            </div>
        </form>
    </div>
</div>

{{-- Approve Return Modal --}}
<div id="approve-return-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-slate-900">Approve Return — <span id="approve-order-number" class="text-emerald-600 font-bold"></span></h3>
            <button onclick="closeApproveModal()" class="text-slate-400 hover:text-slate-600"><i data-lucide="x" class="h-5 w-5"></i></button>
        </div>
        <div class="p-6 space-y-4">
            <div class="bg-slate-50 p-4 rounded-xl space-y-2 text-xs text-slate-600">
                <div class="flex justify-between">
                    <span>Total Order Amount:</span>
                    <span class="font-semibold text-slate-900">₹<span id="approve-modal-total-amount">0.00</span></span>
                </div>
                <div class="flex justify-between text-rose-600">
                    <span>Deduction (Shipping):</span>
                    <span class="font-semibold">− ₹<span id="approve-modal-deduction">100.00</span></span>
                </div>
                <div class="flex justify-between border-t border-slate-200 pt-2 font-bold text-sm text-slate-900">
                    <span>Net Refund:</span>
                    <span class="text-emerald-600 font-extrabold">₹<span id="approve-modal-net-refund">0.00</span></span>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Return Shipping Charge (Deducted Amount)</label>
                <input type="number" id="approve-shipping-charge" min="0" step="1" value="100" 
                    class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                    oninput="calculateNetRefund()">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Admin Note (Optional)</label>
                <textarea id="approve-admin-note" rows="2" maxlength="500"
                    placeholder="Add an internal note or message..."
                    class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 resize-none">Return approved by admin</textarea>
            </div>
        </div>
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3">
            <button type="button" onclick="closeApproveModal()" class="px-5 py-2.5 text-sm font-medium text-slate-600 hover:text-slate-900">Go Back</button>
            <button type="button" onclick="submitApproveReturn()" class="saas-btn px-6 py-2.5 bg-emerald-600 text-white text-sm font-semibold rounded-xl hover:bg-emerald-700 transition-colors">Confirm Approval & Refund</button>
        </div>
    </div>
</div>

<script>
    let currentApproveOrderId = null;
    let currentApproveTotalAmount = 0.00;

    function approveReturn(orderId, orderNumber, totalAmount) {
        currentApproveOrderId = orderId;
        currentApproveTotalAmount = parseFloat(totalAmount);
        
        document.getElementById('approve-order-number').textContent = '#' + orderNumber;
        document.getElementById('approve-shipping-charge').value = 100;
        document.getElementById('approve-admin-note').value = 'Return approved by admin';
        
        calculateNetRefund();
        
        document.getElementById('approve-return-modal').classList.remove('hidden');
        document.getElementById('approve-return-modal').classList.add('flex');
    }

    function calculateNetRefund() {
        let shippingCharge = parseFloat(document.getElementById('approve-shipping-charge').value) || 0;
        if (shippingCharge < 0) shippingCharge = 0;
        
        let netRefund = Math.max(0, currentApproveTotalAmount - shippingCharge);
        
        document.getElementById('approve-modal-total-amount').textContent = currentApproveTotalAmount.toFixed(2);
        document.getElementById('approve-modal-deduction').textContent = shippingCharge.toFixed(2);
        document.getElementById('approve-modal-net-refund').textContent = netRefund.toFixed(2);
    }

    function closeApproveModal() {
        document.getElementById('approve-return-modal').classList.add('hidden');
        document.getElementById('approve-return-modal').classList.remove('flex');
    }

    function submitApproveReturn() {
        let shippingCharge = parseFloat(document.getElementById('approve-shipping-charge').value) || 0;
        let adminNote = document.getElementById('approve-admin-note').value;
        
        if (shippingCharge < 0) {
            alert('Shipping charge cannot be negative.');
            return;
        }

        fetch('/admin/orders/' + currentApproveOrderId + '/approve-return', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ 
                return_shipping_charge: shippingCharge,
                admin_note: adminNote 
            })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                alert('✅ ' + data.message);
                window.location.reload();
            } else {
                alert('❌ ' + (data.message || 'Failed to approve return'));
            }
        })
        .catch(() => alert('Network error. Please try again.'));
    }

    function rejectReturn(orderId, orderNumber) {
        document.getElementById('reject-order-number').textContent = '#' + orderNumber;
        document.getElementById('reject-return-form').action = '/admin/orders/' + orderId + '/reject-return';
        document.getElementById('reject-return-modal').classList.remove('hidden');
        document.getElementById('reject-return-modal').classList.add('flex');
    }

    function closeRejectModal() {
        document.getElementById('reject-return-modal').classList.add('hidden');
        document.getElementById('reject-return-modal').classList.remove('flex');
    }
</script>
@endsection
