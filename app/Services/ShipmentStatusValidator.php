<?php

namespace App\Services;

class ShipmentStatusValidator
{
    /**
     * Allowed status transitions matrix.
     */
    private const ALLOWED_TRANSITIONS = [
        'processing'       => ['processing', 'shipped', 'cancelled'],
        'shipped'          => ['out_for_delivery', 'returned'],
        'out_for_delivery' => ['delivered', 'failed_delivery', 'returned'],
        'delivered'        => ['returned'],
        'cancelled'        => [],
        'returned'         => [],
        'failed_delivery'  => ['out_for_delivery', 'returned'],
        'lost'             => [],
    ];

    public function isValidTransition(string $currentStatus, string $newStatus): bool
    {
        if (!isset(self::ALLOWED_TRANSITIONS[$currentStatus])) {
            return false;
        }

        return in_array($newStatus, self::ALLOWED_TRANSITIONS[$currentStatus]);
    }

    /**
     * Map NimbusPost status string to internal status.
     *
     * Handles BOTH:
     * 1. Webhook status strings: "in transit", "out for delivery" etc. (lowercase)
     * 2. Tracking API status codes: "PP", "IT", "OFD", "DL", "RT" etc.
     * 3. Bulk API full status strings: "pending pickup", "in transit" etc.
     *
     * Matching is case-insensitive for robustness.
     */
    public function mapNimbusStatus(string $nimbusStatus): ?string
    {
        // Normalise: lowercase + trim
        $normalized = strtolower(trim($nimbusStatus));

        $statusMap = [
            // ─── Official Tracking API Status Codes (from docs) ───────────────
            'pp'                      => 'processing',   // Pending Pickup
            'it'                      => 'shipped',       // In Transit
            'ex'                      => 'failed_delivery', // Exception
            'ofd'                     => 'out_for_delivery', // Out For Delivery
            'dl'                      => 'delivered',    // Delivered
            'rt'                      => 'returned',     // RTO
            'rt-it'                   => 'returned',     // RTO In Transit
            'rt-dl'                   => 'returned',     // RTO Delivered

            // ─── Webhook / Bulk API full status strings (lowercase) ───────────
            // Pickup / Processing
            'pickup scheduled'        => 'processing',
            'pickup pending'          => 'processing',
            'pending pickup'          => 'processing',
            'pickup booked'           => 'processing',
            'manifested'              => 'processing',
            'picked up'               => 'processing',

            // In Transit
            'in transit'              => 'shipped',
            'intransit'               => 'shipped',
            'reached at hub'          => 'shipped',
            'reached nearest hub'     => 'shipped',
            'shipment booked'         => 'shipped',

            // Out For Delivery
            'out for delivery'        => 'out_for_delivery',
            'out_for_delivery'        => 'out_for_delivery',

            // Delivered
            'delivered'               => 'delivered',
            'shipment delivered'      => 'delivered',

            // Delivery Failed / Undelivered / NDR
            'delivery failed'         => 'failed_delivery',
            'undelivered'             => 'failed_delivery',
            'delivery attempt failed' => 'failed_delivery',
            'ndr'                     => 'failed_delivery',
            'exception'               => 'failed_delivery',

            // Returned / RTO
            'returned to seller'      => 'returned',
            'rto initiated'           => 'returned',
            'rto delivered'           => 'returned',
            'rto in transit'          => 'returned',
            'return initiated'        => 'returned',
            'rto'                     => 'returned',

            // Cancelled
            'cancelled'               => 'cancelled',
            'shipment cancelled'      => 'cancelled',

            // Lost
            'lost'                    => 'lost',
            'shipment lost'           => 'lost',
        ];

        return $statusMap[$normalized] ?? null;
    }
}
