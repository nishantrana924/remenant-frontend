@props(['status'])
@php
    $colors = [
        'pending' => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300',
        'processing' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
        'awb_generated' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300',
        'labels_generated' => 'bg-cyan-100 text-cyan-800 dark:bg-cyan-900/30 dark:text-cyan-300',
        'ready_for_pickup' => 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300',
        'dispatched' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
        'completed' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300',
        'frozen' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300',
        'manual_review' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
        'warning' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
        'error' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300'
    ];
    $colorClass = $colors[$status] ?? $colors['pending'];
    $label = ucwords(str_replace('_', ' ', $status));
@endphp
<span {{ $attributes->merge(['class' => "inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium $colorClass"]) }}>
    {{ $label }}
</span>
