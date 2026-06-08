@props(['title', 'value', 'color' => 'blue', 'isLoading' => false, 'trend' => null])
<div {{ $attributes->merge(['class' => 'bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 p-6 flex items-center justify-between hover:shadow-md transition-shadow']) }}>
    @if($isLoading)
        <div class="animate-pulse flex-1">
            <div class="h-4 bg-gray-200 dark:bg-slate-700 rounded w-1/2 mb-2"></div>
            <div class="h-8 bg-gray-200 dark:bg-slate-700 rounded w-3/4"></div>
        </div>
    @else
        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">{{ $title }}</p>
            <div class="flex items-baseline space-x-2">
                <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $value }}</h3>
                @if($trend)
                    <span class="text-sm font-medium {{ $trend > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                        {{ $trend > 0 ? '+' : '' }}{{ $trend }}%
                    </span>
                @endif
            </div>
        </div>
        <div class="p-3 rounded-full bg-{{ $color }}-50 dark:bg-{{ $color }}-900/30 text-{{ $color }}-600 dark:text-{{ $color }}-400">
            {{ $icon ?? '' }}
        </div>
    @endif
</div>
