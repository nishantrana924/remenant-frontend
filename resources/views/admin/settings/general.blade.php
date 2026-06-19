@extends('admin.layouts.app')

@section('title', 'General Settings - Admin')

@section('header')
<div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">General Settings</h1>
        <p class="text-sm font-medium text-slate-500 mt-1">Manage storefront details, banners, and marquees</p>
    </div>
</div>
@endsection

@section('content')
<div class="max-w-4xl">
    <form action="{{ route('admin.settings.general.update') }}" method="POST" class="space-y-8" data-settings-form>
        @csrf

        <!-- Top Marquee Settings -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-orange-100 flex items-center justify-center shrink-0">
                        <i data-lucide="megaphone" class="w-5 h-5 text-orange-600"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Top Header Marquee</h2>
                        <p class="text-xs font-medium text-slate-500 mt-0.5">Scrolling text bar at the top of the homepage</p>
                    </div>
                </div>
                
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="top_marquee_active" value="1" class="sr-only peer" {{ $settings['top_marquee_active'] === '1' ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-100 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-500"></div>
                    <span class="ml-3 text-sm font-bold text-slate-700">Active</span>
                </label>
            </div>

            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <label class="block text-sm font-bold text-slate-700">Marquee Items</label>
                        <button type="button" onclick="addMarqueeItem()" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-orange-600 bg-orange-50 hover:bg-orange-100 rounded-lg transition-colors">
                            <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                            Add Item
                        </button>
                    </div>
                    
                    <div id="marquee-items-container" class="space-y-3">
                        @forelse($settings['top_marquee_items'] as $index => $item)
                            <div class="flex items-center gap-3 marquee-item-row">
                                <div class="flex-1">
                                    <input type="text" name="marquee_items[]" value="{{ $item }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 focus:bg-white transition-all outline-none" placeholder="e.g. Free delivery over ₹999">
                                </div>
                                <button type="button" onclick="this.closest('.marquee-item-row').remove()" class="w-10 h-10 inline-flex items-center justify-center rounded-xl text-red-500 hover:bg-red-50 hover:text-red-600 transition-colors shrink-0">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>
                        @empty
                            <div class="flex items-center gap-3 marquee-item-row">
                                <div class="flex-1">
                                    <input type="text" name="marquee_items[]" value="" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 focus:bg-white transition-all outline-none" placeholder="e.g. Free delivery over ₹999">
                                </div>
                                <button type="button" onclick="this.closest('.marquee-item-row').remove()" class="w-10 h-10 inline-flex items-center justify-center rounded-xl text-red-500 hover:bg-red-50 hover:text-red-600 transition-colors shrink-0">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Save Actions -->
        <div class="flex items-center justify-end gap-3 pt-4">
            <button type="submit" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 text-sm font-bold text-white bg-slate-900 hover:bg-slate-800 rounded-xl transition-all active:scale-[0.98]">
                <i data-lucide="save" class="w-4 h-4"></i>
                Save Changes
            </button>
        </div>
    </form>
</div>

<!-- Template for new marquee items -->
<template id="marquee-item-template">
    <div class="flex items-center gap-3 marquee-item-row mt-3">
        <div class="flex-1">
            <input type="text" name="marquee_items[]" value="" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 focus:bg-white transition-all outline-none" placeholder="Enter marquee text...">
        </div>
        <button type="button" onclick="this.closest('.marquee-item-row').remove()" class="w-10 h-10 inline-flex items-center justify-center rounded-xl text-red-500 hover:bg-red-50 hover:text-red-600 transition-colors shrink-0">
            <i data-lucide="trash-2" class="w-4 h-4"></i>
        </button>
    </div>
</template>

<script>
    function addMarqueeItem() {
        const container = document.getElementById('marquee-items-container');
        const template = document.getElementById('marquee-item-template');
        const clone = template.content.cloneNode(true);
        container.appendChild(clone);
        if (window.lucide) {
            window.lucide.createIcons();
        }
    }
</script>
@endsection
