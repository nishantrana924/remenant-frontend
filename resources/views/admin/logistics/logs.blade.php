@extends('admin.layouts.app')

@section('content')
<div class="page-enter space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.logistics.dashboard') }}" class="h-8 w-8 rounded bg-white border border-slate-200 flex items-center justify-center text-slate-500 hover:text-slate-900 transition-all shadow-sm">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
            </a>
            <div>
                <h1 class="text-lg font-bold text-slate-900 leading-tight">API Logs</h1>
                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mt-0.5">Logistics / API Transactions</p>
            </div>
        </div>
    </div>

    {{-- Logs Table --}}
    <div class="bg-white border border-slate-200 rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-[9px] font-bold uppercase tracking-widest text-slate-400 bg-slate-50 border-b border-slate-100">
                        <th class="px-4 py-3 text-left">Action</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">Payload</th>
                        <th class="px-4 py-3 text-left">Response</th>
                        <th class="px-4 py-3 text-right">Timestamp</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($logs as $log)
                    <tr class="hover:bg-slate-50/50 transition-all group">
                        <td class="px-4 py-3">
                            <p class="text-[11px] font-bold text-slate-800 uppercase">{{ str_replace('/', ' / ', $log->action) }}</p>
                            <p class="text-[9px] text-slate-400 font-bold uppercase mt-0.5">Log #{{ $log->id }}</p>
                        </td>
                        <td class="px-4 py-3">
                            @php
                                $color = $log->status === 'success'
                                    ? 'bg-emerald-50 text-emerald-700 border-emerald-200'
                                    : 'bg-rose-50 text-rose-700 border-rose-200';
                            @endphp
                            <span class="{{ $color }} px-2.5 py-0.5 rounded text-[9px] font-bold uppercase tracking-widest border">
                                {{ $log->status }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <button onclick="viewLogJson('Payload', {{ json_encode($log->request_payload) }})"
                                class="h-7 px-3 rounded border border-slate-200 bg-slate-50 text-[10px] font-bold text-slate-600 hover:bg-white hover:border-slate-300 transition-all uppercase tracking-widest">
                                View JSON
                            </button>
                        </td>
                        <td class="px-4 py-3">
                            <button onclick="viewLogJson('Response', {{ json_encode($log->response_data) }})"
                                class="h-7 px-3 rounded border border-slate-200 bg-slate-50 text-[10px] font-bold text-slate-600 hover:bg-white hover:border-slate-300 transition-all uppercase tracking-widest">
                                View JSON
                            </button>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <span class="text-[10px] font-bold text-slate-400 uppercase">{{ $log->created_at->format('M d, H:i:s') }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-16 text-center">
                            <i data-lucide="terminal" class="w-8 h-8 text-slate-200 mx-auto mb-3"></i>
                            <p class="text-[11px] text-slate-400 font-bold uppercase">No API logs yet</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
        <div class="px-4 py-3 border-t border-slate-100 bg-slate-50/30">
            {{ $logs->links() }}
        </div>
        @endif
    </div>
</div>

{{-- JSON Viewer Modal --}}
<div id="json-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[9999] hidden flex items-center justify-center p-4">
    <div class="bg-slate-900 rounded-lg w-full max-w-3xl shadow-2xl overflow-hidden max-h-[80vh] flex flex-col border border-slate-700">
        <div class="px-5 py-4 border-b border-white/5 flex items-center justify-between">
            <h3 id="json-modal-title" class="text-sm font-bold text-white uppercase tracking-wide">API Data</h3>
            <button onclick="closeLogJson()" class="h-8 w-8 rounded hover:bg-white/10 flex items-center justify-center transition-all">
                <i data-lucide="x" class="w-4 h-4 text-slate-400"></i>
            </button>
        </div>
        <div class="p-5 overflow-y-auto flex-1 bg-slate-950 font-mono text-[11px] text-blue-300 whitespace-pre scrollbar-dark">
            <code id="json-viewer"></code>
        </div>
    </div>
</div>

<script>
function viewLogJson(title, data) {
    document.getElementById('json-modal-title').innerText = title + ' Data';
    document.getElementById('json-viewer').innerText = JSON.stringify(data, null, 4);
    document.getElementById('json-modal').classList.remove('hidden');
    lucide.createIcons();
}
function closeLogJson() {
    document.getElementById('json-modal').classList.add('hidden');
}
</script>

<style>
.scrollbar-dark::-webkit-scrollbar { width: 6px; }
.scrollbar-dark::-webkit-scrollbar-track { background: #020617; }
.scrollbar-dark::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 6px; }
.scrollbar-dark::-webkit-scrollbar-thumb:hover { background: #334155; }
</style>
@endsection
