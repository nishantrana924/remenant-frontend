@extends('admin.layouts.app')

@section('content')
<div class="page-enter space-y-6" x-data="{
    loading: false,
    async takeAction(awb, action) {
        if(!confirm('Are you sure you want to ' + action.replace('-', ' ') + '?')) return;
        this.loading = true;
        try {
            const res = await fetch('{{ route('admin.logistics.ndr.action') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ shipment_id: awb, action: action })
            });
            const json = await res.json();
            if(json.success) {
                window.toast(json.message, 'success');
                setTimeout(() => window.location.reload(), 1500);
            } else {
                window.toast(json.message, 'error');
            }
        } catch (e) {
            window.toast('Action failed', 'error');
        } finally {
            this.loading = false;
        }
    }
}">

    {{-- Loading Overlay --}}
    <div x-show="loading" x-cloak class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-[200] flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg border border-slate-200 shadow-xl flex flex-col items-center gap-3">
            <div class="w-8 h-8 border-4 border-slate-100 border-t-blue-500 rounded-full animate-spin"></div>
            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Submitting Action...</p>
        </div>
    </div>

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.logistics.dashboard') }}" class="h-8 w-8 rounded bg-white border border-slate-200 flex items-center justify-center text-slate-500 hover:text-slate-900 transition-all shadow-sm">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
            </a>
            <div>
                <h1 class="text-lg font-bold text-slate-900 leading-tight">NDR Management</h1>
                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mt-0.5">Logistics / Non-Delivery Reports</p>
            </div>
        </div>
        <div class="flex items-center gap-2 px-3 py-1.5 bg-white border border-slate-200 rounded shadow-sm">
            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
            <span class="text-[10px] font-bold uppercase tracking-widest text-slate-600">Nimbus API Live</span>
        </div>
    </div>

    {{-- NDR Table --}}
    <div class="bg-white border border-slate-200 rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-[9px] font-bold uppercase tracking-widest text-slate-400 bg-slate-50 border-b border-slate-100">
                        <th class="px-4 py-3 text-left">AWB Number</th>
                        <th class="px-4 py-3 text-left">Order #</th>
                        <th class="px-4 py-3 text-left">Customer</th>
                        <th class="px-4 py-3 text-left">Failure Reason</th>
                        <th class="px-4 py-3 text-left">Attempts</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-right">Decision</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($items as $item)
                    <tr class="hover:bg-slate-50/50 transition-all">
                        <td class="px-4 py-3">
                            <span class="text-[11px] font-black text-blue-600">{{ $item['awb_number'] ?? '-' }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-[11px] font-bold text-slate-700">{{ $item['order_number'] ?? '-' }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <p class="text-[11px] font-bold text-slate-800">{{ $item['consignee_name'] ?? 'Guest' }}</p>
                            <p class="text-[9px] text-slate-400">{{ $item['consignee_phone'] ?? '' }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <p class="text-[11px] font-medium text-rose-500 max-w-[180px] leading-tight">{{ $item['reason'] ?? 'Delivery Failed' }}</p>
                            <p class="text-[9px] text-slate-400 uppercase font-bold mt-0.5">{{ date('M d, H:i', strtotime($item['last_attempt_date'] ?? now())) }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-[11px] font-black text-slate-900">{{ $item['attempts'] ?? 0 }} / 3</span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="bg-slate-50 text-slate-600 px-2.5 py-0.5 rounded text-[9px] font-bold uppercase tracking-widest border border-slate-200">
                                {{ $item['ndr_status'] ?? 'Pending' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <button @click="takeAction('{{ $item['awb_number'] }}', 're-attempt')"
                                    class="h-7 px-3 rounded bg-slate-900 text-white text-[9px] font-bold uppercase tracking-widest hover:bg-blue-600 transition-all">
                                    Re-attempt
                                </button>
                                <div class="relative group/menu">
                                    <button class="h-7 w-7 rounded border border-slate-200 flex items-center justify-center text-slate-400 hover:bg-slate-50 transition-all">
                                        <i data-lucide="more-vertical" class="w-3.5 h-3.5"></i>
                                    </button>
                                    <div class="absolute right-0 top-full mt-1 w-44 bg-white rounded-lg shadow-xl border border-slate-100 py-1 hidden group-hover/menu:block z-50">
                                        <button @click="takeAction('{{ $item['awb_number'] }}', 'rto')"
                                            class="w-full px-4 py-2 text-left text-[10px] font-bold uppercase text-rose-500 hover:bg-rose-50 transition-all">
                                            RTO (Return)
                                        </button>
                                        <button @click="takeAction('{{ $item['awb_number'] }}', 'change_address')"
                                            class="w-full px-4 py-2 text-left text-[10px] font-bold uppercase text-slate-600 hover:bg-slate-50 transition-all">
                                            Change Address
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-16 text-center">
                            <i data-lucide="check-circle-2" class="w-8 h-8 text-emerald-400 mx-auto mb-3 opacity-50"></i>
                            <p class="text-[11px] text-slate-400 font-bold uppercase">No pending NDRs found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
