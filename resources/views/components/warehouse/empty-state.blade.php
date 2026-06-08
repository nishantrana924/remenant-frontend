@props(['title', 'description'])
<div {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center p-12 text-center bg-white dark:bg-slate-800 rounded-xl border border-dashed border-gray-300 dark:border-slate-600']) }}>
    <div class="text-gray-400 dark:text-slate-500 mb-4">{{ $icon ?? '' }}</div>
    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-1">{{ $title }}</h3>
    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $description }}</p>
    @if(isset($actions))
        <div class="mt-6">{{ $actions }}</div>
    @endif
</div>
