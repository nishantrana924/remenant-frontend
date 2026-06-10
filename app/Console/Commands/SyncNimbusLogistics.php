<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Shipment;
use App\Models\Warehouse;
use App\Models\Order;
use App\Services\NimbusPostService;
use App\Services\ShipmentStatusValidator;
use Illuminate\Support\Facades\Log;

class SyncNimbusLogistics extends Command
{
    protected $signature = 'nimbus:sync {--type=all}';
    protected $description = 'Synchronize all data from NimbusPost (Statuses, NDRs, Warehouses)';

    protected $nimbus;
    protected $validator;

    public function __construct(NimbusPostService $nimbus, ShipmentStatusValidator $validator)
    {
        parent::__construct();
        $this->nimbus    = $nimbus;
        $this->validator = $validator;
    }

    public function handle()
    {
        $type = $this->option('type');

        if ($type === 'all' || $type === 'status') {
            $this->info('Syncing Shipment Statuses...');
            $this->syncStatuses();
        }

        if ($type === 'all' || $type === 'ndr') {
            $this->info('Syncing NDR Data...');
            $this->syncNDR();
        }

        $this->info('Synchronization completed successfully!');
    }

    protected function syncStatuses()
    {
        // Get all active shipments with AWB numbers that are not in terminal states
        $activeShipments = Shipment::whereNotIn('status', ['delivered', 'cancelled', 'rto_delivered'])
            ->whereNotNull('awb_number')
            ->get();

        if ($activeShipments->isEmpty()) {
            $this->comment('No active shipments to sync.');
            return;
        }

        // Use Bulk Tracking API (up to 100 AWBs per call) — more efficient than one-by-one
        $chunks = $activeShipments->chunk(100);

        foreach ($chunks as $chunk) {
            $awbs = $chunk->pluck('awb_number')->toArray();

            $this->comment('Fetching tracking for ' . count($awbs) . ' AWBs...');

            $response = $this->nimbus->trackBulk($awbs);

            if (!($response['status'] ?? false)) {
                Log::error('NimbusPost Bulk Track failed', ['response' => $response]);
                $this->error('Bulk tracking API call failed. Check logs.');
                continue;
            }

            // Index shipments by AWB for quick lookup
            $shipmentsByAwb = $chunk->keyBy('awb_number');

            foreach ($response['data'] ?? [] as $trackData) {
                $awb = $trackData['awb_number'] ?? null;
                if (!$awb) continue;

                $shipment = $shipmentsByAwb->get($awb);
                if (!$shipment) continue;

                // NimbusPost bulk API returns full status strings like "pending pickup", "in transit"
                $rawStatus    = $trackData['status'] ?? null;
                $mappedStatus = $rawStatus ? $this->validator->mapNimbusStatus($rawStatus) : null;

                if (!$mappedStatus) {
                    $this->line("  AWB {$awb}: Unrecognized status '{$rawStatus}' — skipped.");
                    Log::warning("SyncNimbusLogistics: Unrecognized status '{$rawStatus}' for AWB {$awb}");
                    continue;
                }

                // Update shipment record
                $oldStatus = $shipment->status;
                $shipment->update([
                    'status'       => $mappedStatus,
                    'delivered_at' => ($mappedStatus === 'delivered' && !$shipment->delivered_at) ? now() : $shipment->delivered_at,
                ]);

                // Update parent order
                $order = $shipment->order;
                if ($order) {
                    $order->update([
                        'status'          => $mappedStatus,
                        'delivery_status' => $mappedStatus, // ✅ Use mapped status, not raw string
                    ]);

                    if ($oldStatus !== $mappedStatus) {
                        if (method_exists($order, 'logStatus')) {
                            $order->logStatus("Sync: Status updated from {$oldStatus} to {$mappedStatus} via NimbusPost polling.");
                        }
                        $this->line("  AWB {$awb}: {$oldStatus} → {$mappedStatus}");
                    }
                }

                // Sync tracking history from NimbusPost
                // Official history fields: status_code, location, event_time, message
                if (isset($trackData['history']) && is_array($trackData['history'])) {
                    foreach ($trackData['history'] as $event) {
                        $eventTime   = $event['event_time'] ?? null;
                        $statusCode  = $event['status_code'] ?? null;
                        $location    = $event['location'] ?? null;
                        $message     = $event['message'] ?? null;

                        if (!$eventTime || !$statusCode) continue;

                        $shipment->trackings()->updateOrCreate(
                            [
                                'activity' => $statusCode,
                                'event_at' => date('Y-m-d H:i:s', strtotime($eventTime)),
                            ],
                            [
                                'status'   => $this->validator->mapNimbusStatus($statusCode) ?? strtolower($statusCode),
                                'location' => $location,
                                'message'  => $message,
                            ]
                        );
                    }
                }
            }
        }

        $this->comment('Shipment statuses synced.');
    }

    protected function syncNDR()
    {
        $response = $this->nimbus->getNDR();
        if ($response['status'] ?? false) {
            $count = count($response['data'] ?? []);
            $this->comment("NDR data fetched: {$count} records.");
            // Add NDR storage logic here if a local ndr table exists
        } else {
            $this->error('NDR fetch failed.');
        }
    }
}
