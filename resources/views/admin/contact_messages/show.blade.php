@extends('admin.layouts.app')

@section('header')
    <div class="flex items-center justify-between">
        <h2 class="font-bold text-xl text-slate-900 leading-tight">View Message</h2>
        <a href="{{ route('admin.contact-messages.index') }}" class="saas-btn-secondary py-2 px-4 text-xs">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i> Back to Messages
        </a>
    </div>
@endsection

@section('content')
<div class="max-w-4xl">
    <div class="saas-card p-8">
        <div class="mb-8 border-b border-slate-100 pb-6 flex items-start justify-between">
            <div>
                <h3 class="text-2xl font-bold text-slate-900">{{ $message->subject ?? 'No Subject Provided' }}</h3>
                <p class="text-sm text-slate-500 mt-2 flex items-center gap-2">
                    <i data-lucide="clock" class="w-4 h-4"></i>
                    Received on {{ $message->created_at->format('F d, Y \a\t h:i A') }}
                </p>
            </div>
            <form action="{{ route('admin.contact-messages.destroy', $message->id) }}" method="POST" onsubmit="return confirm('Delete this message permanently?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="saas-btn-danger px-4 py-2 text-xs flex items-center gap-1">
                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Delete Message
                </button>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Sender Name</p>
                <p class="text-base font-semibold text-slate-900">{{ $message->name }}</p>
            </div>
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Email Address</p>
                <a href="mailto:{{ $message->email }}" class="text-base font-semibold text-blue-600 hover:text-blue-800 flex items-center gap-1">
                    {{ $message->email }} <i data-lucide="external-link" class="w-3 h-3"></i>
                </a>
            </div>
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Phone Number</p>
                <p class="text-base font-semibold text-slate-900">{{ $message->phone ?? 'Not provided' }}</p>
            </div>
        </div>

        <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100">
            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4">Message Content</p>
            <div class="prose prose-sm prose-slate max-w-none whitespace-pre-wrap text-slate-700 leading-relaxed font-medium">
                {{ $message->message }}
            </div>
        </div>
    </div>
</div>
@endsection
