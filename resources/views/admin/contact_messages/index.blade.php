@extends('admin.layouts.app')

@section('header')
    <h2 class="font-bold text-xl text-slate-900 leading-tight">Contact Messages</h2>
@endsection

@section('content')
<div class="saas-card overflow-hidden border border-slate-100 p-0 shadow-sm sm:rounded-2xl">
    <table class="min-w-full divide-y divide-slate-100">
        <thead class="bg-slate-50/80">
            <tr>
                <th scope="col" class="py-4 pl-6 pr-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-400">Date</th>
                <th scope="col" class="px-3 py-4 text-left text-[11px] font-bold uppercase tracking-wider text-slate-400">Name</th>
                <th scope="col" class="px-3 py-4 text-left text-[11px] font-bold uppercase tracking-wider text-slate-400">Email</th>
                <th scope="col" class="px-3 py-4 text-left text-[11px] font-bold uppercase tracking-wider text-slate-400">Subject</th>
                <th scope="col" class="relative py-4 pl-3 pr-6 text-right">
                    <span class="sr-only">Actions</span>
                </th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 bg-white">
            @forelse($messages as $message)
                <tr class="transition-colors hover:bg-slate-50/50">
                    <td class="whitespace-nowrap py-4 pl-6 pr-3 text-sm text-slate-500 font-medium">
                        {{ $message->created_at->format('M d, Y h:i A') }}
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm font-semibold text-slate-900">
                        {{ $message->name }}
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-500">
                        {{ $message->email }}
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-500">
                        {{ $message->subject ?? 'N/A' }}
                    </td>
                    <td class="relative whitespace-nowrap py-4 pl-3 pr-6 text-right text-sm font-medium">
                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('admin.contact-messages.show', $message->id) }}" class="text-blue-600 hover:text-blue-900 flex items-center gap-1 font-bold text-xs uppercase tracking-wider"><i data-lucide="eye" class="w-3.5 h-3.5"></i> View</a>
                            <form action="{{ route('admin.contact-messages.destroy', $message->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this message?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 flex items-center gap-1 font-bold text-xs uppercase tracking-wider">
                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-sm text-slate-500 bg-white">
                        <div class="flex flex-col items-center justify-center space-y-3">
                            <div class="p-3 bg-slate-50 rounded-full">
                                <i data-lucide="inbox" class="w-8 h-8 text-slate-300"></i>
                            </div>
                            <p class="font-medium">No contact messages found.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    @if($messages->hasPages())
        <div class="border-t border-slate-100 px-6 py-4 bg-white">
            {{ $messages->links() }}
        </div>
    @endif
</div>
@endsection
