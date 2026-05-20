<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Order;

class NimbusWebhookController extends Controller
{
    /**
     * Handle incoming NimbusPost webhook events.
     * NimbusPost sends a POST request to this URL with shipment status updates.
     */
    public function handle(Request $request)
    {
        $payload = $request->all();

        Log::info('NimbusPost Webhook Received:', $payload);

        // NimbusPost sends shipment tracking updates
        // Common fields: awb_number, current_status, status_body, etc.
        $awb    = $payload['awb_number'] ?? $payload['waybill'] ?? null;
        $status = $payload['current_status'] ?? $payload['status'] ?? null;

        if (!$awb || !$status) {
            Log::warning('NimbusPost Webhook: Missing awb or status', $payload);
            return response()->json(['status' => 'ignored', 'reason' => 'missing awb or status'], 200);
        }

        // Find the order by AWB / tracking ID
        $order = Order::where('tracking_id', $awb)->first();

        if (!$order) {
            Log::warning("NimbusPost Webhook: No order found for AWB {$awb}");
            return response()->json(['status' => 'ignored', 'reason' => 'order not found'], 200);
        }

        // Map NimbusPost statuses to our internal order statuses
        $mappedStatus = $this->mapNimbusStatus($status);

        Log::info("NimbusPost Webhook: Updating Order #{$order->id} AWB {$awb} -> {$status} => {$mappedStatus}");

        $order->update([
            'delivery_status' => $status,       // Raw nimbus status string
            'status'          => $mappedStatus, // Our internal mapped status
        ]);

        return response()->json(['status' => 'success'], 200);
    }

    /**
     * Map NimbusPost statuses to our internal order status values.
     */
    private function mapNimbusStatus(string $nimbusStatus): string
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

        return $statusMap[$nimbusStatus] ?? 'processing';
    }
}
