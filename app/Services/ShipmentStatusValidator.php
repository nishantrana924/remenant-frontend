<?php

namespace App\Services;

class ShipmentStatusValidator
{
    /**
     * Allowed status transitions matrix as requested.
     */
    private const ALLOWED_TRANSITIONS = [
        'processing' => ['processing', 'shipped', 'cancelled'],
        'shipped' => ['out_for_delivery', 'returned'],
        'out_for_delivery' => ['delivered', 'failed_delivery', 'returned'],
        'delivered' => ['returned'],
        'cancelled' => [],
        'returned' => [],
        'failed_delivery' => ['out_for_delivery', 'returned'],
        'lost' => [],
    ];

    public function isValidTransition(string $currentStatus, string $newStatus): bool
    {
        if (!isset(self::ALLOWED_TRANSITIONS[$currentStatus])) {
            return false; // Terminal or unknown states cannot transition
        }

        return in_array($newStatus, self::ALLOWED_TRANSITIONS[$currentStatus]);
    }

    public function mapNimbusStatus(string $nimbusStatus): ?string
    {
        $statusMap = [
            'Pickup Scheduled'       => 'processing',
            'Pickup Pending'         => 'processing',
            'In Transit'             => 'shipped',
            'Out For Delivery'       => 'out_for_delivery',
            'Delivered'              => 'delivered',
            'Delivery Failed'        => 'failed_delivery',
            'Undelivered'            => 'failed_delivery',
            'Returned to Seller'     => 'returned',
            'RTO Initiated'          => 'returned',
            'RTO Delivered'          => 'returned',
            'Cancelled'              => 'cancelled',
            'Lost'                   => 'lost',
        ];

        return $statusMap[$nimbusStatus] ?? null;
    }
}
