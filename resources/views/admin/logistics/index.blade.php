@extends('admin.layouts.app')

@section('header')
    <div class="flex items-center gap-2">
        <h2 class="font-bold text-xl text-slate-900 leading-tight">Order Fulfillment</h2>
        <span class="h-6 w-6 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-[10px] font-black">
            {{ $items->total() }}
        </span>
    </div>
@endsection

@section('content')
<div class="space-y-6 pb-12">
    <!-- Filters & Tabs -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-1 p-1 bg-white border border-slate-100 rounded-2xl shadow-sm">
            <a href="{{ route('admin.logistics.index') }}" class="px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all {{ !request('status') ? 'bg-slate-900 text-white shadow-lg' : 'text-slate-400 hover:text-slate-600' }}">All</a>
            <a href="{{ route('admin.logistics.index', ['status' => 'pending']) }}" class="px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all {{ request('status') == 'pending' ? 'bg-orange-500 text-white shadow-lg' : 'text-slate-400 hover:text-slate-600' }}">Pending</a>
            <a href="{{ route('admin.logistics.index', ['status' => 'packed']) }}" class="px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all {{ request('status') == 'packed' ? 'bg-indigo-500 text-white shadow-lg' : 'text-slate-400 hover:text-slate-600' }}">Packed</a>
            <a href="{{ route('admin.logistics.index', ['status' => 'shipped']) }}" class="px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all {{ request('status') == 'shipped' ? 'bg-blue-500 text-white shadow-lg' : 'text-slate-400 hover:text-slate-600' }}">Shipped</a>
        </div>
        
        <div class="relative">
            <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
            <input type="text" placeholder="Search AWB or Order ID..." class="pl-11 pr-6 py-3 bg-white border-slate-100 rounded-2xl text-[10px] font-bold uppercase tracking-widest w-full sm:w-72 focus:ring-2 focus:ring-blue-500/20 shadow-sm">
        </div>
    </div>

    <!-- Orders Table -->
    <div class="saas-card overflow-hidden">
        <table class="w-full text-left">
            <thead>
                <tr class="text-[8px] font-black uppercase tracking-widest text-slate-400 bg-slate-50/50">
                    <th class="px-6 py-4">Order Details</th>
                    <th class="px-6 py-4">Customer & Address</th>
                    <th class="px-6 py-4">Shipping Info</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($items as $order)
                <tr class="hover:bg-slate-50/50 transition-all cursor-default">
                    <td class="px-6 py-6">
                        <div class="flex items-center gap-4">
                            <div class="h-12 w-12 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center justify-center text-slate-400">
                                <i data-lucide="shopping-bag" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-900 uppercase tracking-tight">#{{ $order->order_number }}</p>
                                <p class="text-[9px] font-bold text-slate-400 uppercase mt-0.5">{{ $order->created_at->format('d M, h:i A') }}</p>
                                <div class="mt-2 flex items-center gap-2">
                                    <span class="text-[8px] font-black bg-slate-100 text-slate-500 px-2 py-0.5 rounded uppercase">{{ $order->payment_method }}</span>
                                    <p class="text-[10px] font-bold text-slate-900">₹{{ number_format($order->total_amount, 2) }}</p>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-6">
                        <p class="text-[11px] font-bold text-slate-900 uppercase">{{ $order->customer_name }}</p>
                        <p class="text-[10px] text-slate-400 mt-1 line-clamp-1 uppercase tracking-tighter">{{ $order->address }}</p>
                        <p class="text-[9px] font-bold text-slate-500 mt-1 uppercase">{{ $order->city }}, {{ $order->state }} - {{ $order->pincode }}</p>
                    </td>
                    <td class="px-6 py-6">
                        @if($order->shipment)
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <span class="text-[9px] font-black text-blue-500 uppercase">{{ $order->shipment->awb_number }}</span>
                                <button onclick="copyToClipboard('{{ $order->shipment->awb_number }}')" class="text-slate-300 hover:text-blue-500 transition-all">
                                    <i data-lucide="copy" class="w-3 h-3"></i>
                                </button>
                            </div>
                            <p class="text-[9px] font-bold text-slate-400 uppercase">{{ $order->shipment->courier_name }}</p>
                        </div>
                        @else
                        <span class="text-[9px] font-black text-slate-300 uppercase tracking-widest italic">Not Shipped Yet</span>
                        @endif
                    </td>
                    <td class="px-6 py-6">
                        @php
                            $statusColors = [
                                'pending' => 'bg-orange-50 text-orange-600',
                                'processing' => 'bg-blue-50 text-blue-600',
                                'packed' => 'bg-indigo-50 text-indigo-600',
                                'shipped' => 'bg-emerald-50 text-emerald-600',
                            ];
                            $color = $statusColors[$order->status] ?? 'bg-slate-50 text-slate-500';
                        @endphp
                        <span class="text-[8px] font-black {{ $color }} px-2 py-1 rounded-full uppercase tracking-widest shadow-sm border border-current opacity-80">
                            {{ str_replace('_', ' ', $order->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-6 text-right">
                        <div class="flex justify-end gap-2">
                            @if(!$order->shipment)
                            <button onclick="openShipModal({{ $order->id }}, '#{{ $order->order_number }}')" class="h-9 w-9 rounded-xl bg-blue-600 text-white flex items-center justify-center hover:bg-blue-700 transition-all shadow-lg shadow-blue-100" title="Ship Now">
                                <i data-lucide="send" class="w-4 h-4"></i>
                            </button>
                            @else
                            <a href="{{ $order->shipment->label_url }}" target="_blank" class="h-9 w-9 rounded-xl bg-slate-900 text-white flex items-center justify-center hover:bg-black transition-all shadow-lg shadow-slate-100" title="Download Label">
                                <i data-lucide="printer" class="w-4 h-4"></i>
                            </a>
                            <button onclick="trackAWB('{{ $order->shipment->awb_number }}')" class="h-9 w-9 rounded-xl bg-white border border-slate-100 text-slate-400 flex items-center justify-center hover:bg-slate-50 transition-all shadow-sm" title="Track Shipment">
                                <i data-lucide="map-pin" class="w-4 h-4"></i>
                            </button>
                            @endif
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="h-9 w-9 rounded-xl bg-white border border-slate-100 text-slate-400 flex items-center justify-center hover:bg-slate-50 transition-all shadow-sm">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="px-6 py-4 border-t border-slate-50">
            {{ $items->links() }}
        </div>
    </div>
</div>

<!-- Shipment Modal -->
<div id="ship-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[9999] hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-[2rem] w-full max-w-lg shadow-2xl overflow-hidden animate-in zoom-in duration-300">
        <div class="p-8 border-b border-slate-50 flex items-center justify-between">
            <div>
                <h3 class="text-xl font-bold text-slate-900 uppercase tracking-tight">Generate Shipment</h3>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Order <span id="modal-order-number"></span></p>
            </div>
            <button onclick="closeShipModal()" class="h-10 w-10 rounded-xl hover:bg-slate-50 flex items-center justify-center transition-all">
                <i data-lucide="x" class="w-5 h-5 text-slate-400"></i>
            </button>
        </div>
        
        <form id="ship-form" class="p-8 space-y-6">
            <input type="hidden" id="modal-order-id">
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Package Weight (Grams)</label>
                    <input type="number" name="weight" value="500" class="w-full bg-slate-50 border-slate-100 rounded-xl px-4 py-2.5 text-sm font-bold">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Length (cm)</label>
                    <input type="number" name="length" value="10" class="w-full bg-slate-50 border-slate-100 rounded-xl px-4 py-2.5 text-sm font-bold">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Width (cm)</label>
                    <input type="number" name="width" value="10" class="w-full bg-slate-50 border-slate-100 rounded-xl px-4 py-2.5 text-sm font-bold">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Height (cm)</label>
                    <input type="number" name="height" value="10" class="w-full bg-slate-50 border-slate-100 rounded-xl px-4 py-2.5 text-sm font-bold">
                </div>
            </div>
            
            <div class="pt-4">
                <button type="submit" class="w-full saas-btn-primary py-3">Confirm & Generate AWB</button>
            </div>
        </form>
    </div>
</div>

<script>
function openShipModal(id, number) {
    document.getElementById('modal-order-id').value = id;
    document.getElementById('modal-order-number').innerText = number;
    document.getElementById('ship-modal').classList.remove('hidden');
}

function closeShipModal() {
    document.getElementById('ship-modal').classList.add('hidden');
}

document.getElementById('ship-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const id = document.getElementById('modal-order-id').value;
    const btn = e.target.querySelector('button');
    btn.disabled = true;
    btn.innerHTML = '<i data-lucide="loader-2" class="w-4 h-4 animate-spin mr-2"></i> Pushing to NimbusPost...';
    lucide.createIcons();

    fetch(`/admin/logistics/create-shipment/${id}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(Object.fromEntries(new FormData(e.target)))
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            window.up.reload('.saas-card');
            closeShipModal();
            alert(data.message);
        } else {
            alert(data.message);
            btn.disabled = false;
            btn.innerHTML = 'Confirm & Generate AWB';
        }
    });
});

function trackAWB(awb) {
    alert("Tracking feature coming in next update for AWB: " + awb);
}
</script>
@endsection
