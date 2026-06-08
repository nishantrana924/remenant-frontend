<div {{ $attributes->merge(['class' => 'overflow-x-auto bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700']) }}>
    <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-700">
        <thead class="bg-gray-50 dark:bg-slate-900/50">
            {{ $header }}
        </thead>
        <tbody class="bg-white dark:bg-slate-800 divide-y divide-gray-200 dark:divide-slate-700">
            {{ $slot }}
        </tbody>
    </table>
</div>
