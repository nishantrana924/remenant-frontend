@props(['status', 'size' => 'sm'])

@php
/**
 * NimbusPost Status Badge Component
 *
 * Maps internal status codes to the exact display labels NimbusPost uses,
 * with correct colors and icons for both user and admin dashboards.
 *
 * Usage: <x-status-badge :status="$order->status" />
 *        <x-status-badge :status="$order->delivery_status" size="lg" />
 */

$statusMap = [
    // Internal status   => [label (NimbusPost exact), icon, color classes]
    'pending'            => ['label' => 'Pending',           'icon' => 'clock',          'bg' => 'bg-orange-50',  'text' => 'text-orange-600',  'border' => 'border-orange-200',  'dot' => 'bg-orange-500'],
    'processing'         => ['label' => 'Processing',        'icon' => 'package',         'bg' => 'bg-blue-50',    'text' => 'text-blue-600',    'border' => 'border-blue-200',    'dot' => 'bg-blue-500'],
    'packed'             => ['label' => 'Packed',            'icon' => 'archive',         'bg' => 'bg-purple-50',  'text' => 'text-purple-600',  'border' => 'border-purple-200',  'dot' => 'bg-purple-500'],
    'shipped'            => ['label' => 'In Transit',        'icon' => 'truck',           'bg' => 'bg-indigo-50',  'text' => 'text-indigo-600',  'border' => 'border-indigo-200',  'dot' => 'bg-indigo-500'],
    'out_for_delivery'   => ['label' => 'Out For Delivery',  'icon' => 'navigation',      'bg' => 'bg-sky-50',     'text' => 'text-sky-600',     'border' => 'border-sky-200',     'dot' => 'bg-sky-500'],
    'delivered'          => ['label' => 'Delivered',         'icon' => 'check-circle',    'bg' => 'bg-emerald-50', 'text' => 'text-emerald-600', 'border' => 'border-emerald-200', 'dot' => 'bg-emerald-500'],
    'failed_delivery'    => ['label' => 'Delivery Failed',   'icon' => 'alert-triangle',  'bg' => 'bg-amber-50',   'text' => 'text-amber-600',   'border' => 'border-amber-200',   'dot' => 'bg-amber-500'],
    'returned'           => ['label' => 'Returned to Seller','icon' => 'corner-up-left',  'bg' => 'bg-rose-50',    'text' => 'text-rose-600',    'border' => 'border-rose-200',    'dot' => 'bg-rose-500'],
    'cancelled'          => ['label' => 'Cancelled',         'icon' => 'x-circle',        'bg' => 'bg-red-50',     'text' => 'text-red-600',     'border' => 'border-red-200',     'dot' => 'bg-red-500'],
    'cancellation_requested' => ['label' => 'Cancel Requested', 'icon' => 'alert-circle', 'bg' => 'bg-rose-50',   'text' => 'text-rose-600',    'border' => 'border-rose-200',    'dot' => 'bg-rose-500'],
    'lost'               => ['label' => 'Lost',              'icon' => 'alert-octagon',   'bg' => 'bg-gray-50',    'text' => 'text-gray-600',    'border' => 'border-gray-200',    'dot' => 'bg-gray-500'],

    // Refund statuses
    'refund_pending'     => ['label' => 'Refund Pending',    'icon' => 'rotate-ccw',      'bg' => 'bg-orange-50',  'text' => 'text-orange-600',  'border' => 'border-orange-200',  'dot' => 'bg-orange-500'],
    'refund_processing'  => ['label' => 'Refund Processing', 'icon' => 'loader',          'bg' => 'bg-blue-50',    'text' => 'text-blue-600',    'border' => 'border-blue-200',    'dot' => 'bg-blue-500'],
    'refund_completed'   => ['label' => 'Refunded',          'icon' => 'check-circle',    'bg' => 'bg-emerald-50', 'text' => 'text-emerald-600', 'border' => 'border-emerald-200', 'dot' => 'bg-emerald-500'],
    'refund_failed'      => ['label' => 'Refund Failed',     'icon' => 'x-circle',        'bg' => 'bg-red-50',     'text' => 'text-red-600',     'border' => 'border-red-200',     'dot' => 'bg-red-500'],

    // Customer-initiated return statuses
    'requested'          => ['label' => 'Return Requested',  'icon' => 'alert-circle',    'bg' => 'bg-amber-50',   'text' => 'text-amber-600',   'border' => 'border-amber-200',   'dot' => 'bg-amber-500'],
    'approved'           => ['label' => 'Return Approved',   'icon' => 'check-circle',    'bg' => 'bg-blue-50',    'text' => 'text-blue-600',    'border' => 'border-blue-200',    'dot' => 'bg-blue-500'],
    'rejected'           => ['label' => 'Return Rejected',   'icon' => 'x-circle',        'bg' => 'bg-red-50',     'text' => 'text-red-600',     'border' => 'border-red-200',     'dot' => 'bg-red-500'],
    'picked_up'          => ['label' => 'Return Picked Up',  'icon' => 'truck',           'bg' => 'bg-indigo-50',  'text' => 'text-indigo-600',  'border' => 'border-indigo-200',  'dot' => 'bg-indigo-500'],
];

$normalized = strtolower(trim($status ?? 'pending'));
$info = $statusMap[$normalized] ?? [
    'label'  => ucwords(str_replace('_', ' ', $normalized)),
    'icon'   => 'help-circle',
    'bg'     => 'bg-slate-50',
    'text'   => 'text-slate-600',
    'border' => 'border-slate-200',
    'dot'    => 'bg-slate-400',
];

$sizeClasses = match($size) {
    'xs' => 'px-2 py-0.5 text-[8px] gap-1',
    'sm' => 'px-2.5 py-1 text-[9px] gap-1',
    'md' => 'px-3 py-1.5 text-xs gap-1.5',
    'lg' => 'px-4 py-2 text-sm gap-2',
    default => 'px-2.5 py-1 text-[9px] gap-1',
};

$dotSize = match($size) {
    'xs', 'sm' => 'w-1.5 h-1.5',
    'md'       => 'w-2 h-2',
    'lg'       => 'w-2.5 h-2.5',
    default    => 'w-1.5 h-1.5',
};
@endphp

<span class="inline-flex items-center font-bold uppercase tracking-widest rounded-full border {{ $info['bg'] }} {{ $info['text'] }} {{ $info['border'] }} {{ $sizeClasses }}">
    <span class="rounded-full {{ $dotSize }} {{ $info['dot'] }} shrink-0"></span>
    {{ $info['label'] }}
</span>
