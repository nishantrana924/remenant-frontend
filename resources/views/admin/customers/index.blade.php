@extends('admin.layouts.app')

@section('header')
    <div class="flex items-center justify-between w-full pr-4">
        <div class="flex flex-col justify-center">
            <h2 class="font-black text-xl text-slate-800 leading-none tracking-tight">Customer Intelligence</h2>
            <div class="flex items-center gap-2 mt-1.5">
                <span class="h-1 w-1 bg-indigo-500 rounded-full animate-pulse"></span>
                <p class="text-[9px] text-slate-400 uppercase tracking-[0.2em] font-black">Managing {{ $items->count() }} Profiles</p>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Customer List Table -->
    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Customer Identity</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Contact</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Account Type</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">LTV</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($items as $item)
                        <tr class="hover:bg-slate-50/30 transition-colors group {{ $item->deleted_at ? 'bg-red-50/40' : '' }}">
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="h-12 w-12 rounded-[1.25rem] {{ $item->deleted_at ? 'bg-slate-100 text-slate-400' : ((int)$item->role_id === 1 ? 'bg-orange-50 text-orange-500' : 'bg-indigo-50 text-indigo-500') }} flex items-center justify-center font-black text-lg flex-shrink-0">
                                        {{ strtoupper(substr($item->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h4 class="font-black {{ $item->deleted_at ? 'text-slate-400 line-through' : 'text-slate-800' }} leading-tight">{{ $item->name }}</h4>
                                            @if($item->deleted_at)
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-red-100 text-red-600 text-[9px] font-black uppercase tracking-widest">
                                                    <i data-lucide="user-x" class="w-2.5 h-2.5"></i> Deactivated
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-[10px] text-slate-400 mt-1 font-bold tracking-wide uppercase">ID: #{{ $item->id }} {{ $item->deleted_at ? '• Deleted ' . $item->deleted_at->diffForHumans() : '' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-slate-700">{{ $item->email }}</span>
                                    <span class="text-[10px] text-slate-400">{{ $item->phone ?? 'No phone' }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <form action="{{ route('admin.customers.update-role', $item->id) }}" method="POST" class="inline">
                                    @csrf
                                    <select name="role" onchange="this.form.submit()" class="text-[10px] font-black uppercase tracking-widest border-0 bg-transparent focus:ring-0 cursor-pointer {{ (int)$item->role_id === 1 ? 'text-orange-600' : 'text-indigo-600' }}">
                                        <option value="2" {{ (int)$item->role_id === 2 ? 'selected' : '' }}>Regular User</option>
                                        <option value="1" {{ (int)$item->role_id === 1 ? 'selected' : '' }}>Administrator</option>
                                    </select>
                                </form>
                            </td>
                            <td class="px-8 py-5">
                                <span class="text-sm font-black text-slate-800">₹{{ number_format($item->orders()->where('payment_status', 'paid')->sum('total_amount')) }}</span>
                            </td>
                            <td class="px-8 py-5 text-right flex items-center justify-end gap-3">
                                <a href="{{ route('admin.customers.show', $item->id) }}" class="h-9 w-9 inline-flex items-center justify-center rounded-xl bg-slate-50 text-slate-400 hover:text-indigo-500 hover:bg-indigo-50 transition-all">
                                    <i data-lucide="eye" class="h-4 w-4"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-20 text-center">
                                <p class="text-slate-400 italic">No customers found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
