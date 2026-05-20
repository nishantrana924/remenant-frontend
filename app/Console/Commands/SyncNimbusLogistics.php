<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Shipment;
use App\Models\Warehouse;
use App\Models\Order;
use App\Services\NimbusPostService;
use Illuminate\Support\Facades\Log;

class SyncNimbusLogistics extends Command
{
    protected $signature = 'nimbus:sync {--type=all}';
    protected $description = 'Synchronize all data from NimbusPost (Statuses, NDRs, Warehouses)';

    protected $nimbus;

    public function __construct(NimbusPostService $nimbus)
    {
        parent::__construct();
        $this->nimbus = $nimbus;
    }

    public function handle()
    {
        $type = $this->option('type');

        /*
        if ($type === 'all' || $type === 'warehouses') {
            $this->info('Syncing Warehouses...');
            $this->syncWarehouses();
        }
        */

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

    protected function syncWarehouses()
    {
        $response = $this->nimbus->getWarehouses();
        if ($response['status'] ?? false) {
            foreach ($response['data'] ?? [] as $w) {
                Warehouse::updateOrCreate(
                    ['nimbus_warehouse_id' => $w['id']],
                    [
                        'name' => $w['name'] ?? 'Warehouse',
                        'contact_name' => $w['contact_person'] ?? '',
                        'phone' => $w['phone'] ?? '',
                        'address' => $w['address'] ?? '',
                        'pincode' => $w['pincode'] ?? '',
                        'city' => $w['city'] ?? '',
                        'state' => $w['state'] ?? '',
                        'status' => true
                    ]
                );
            }
            $this->comment('Warehouses synced.');
        }
    }

    protected function syncStatuses()
    {
        $activeShipments = Shipment::whereNotIn('status', ['delivered', 'cancelled', 'rto_delivered'])->get();

        foreach ($activeShipments as $shipment) {
            $response = $this->nimbus->getTracking($shipment->awb_number);
            
            if ($response['status'] ?? false) {
                $data = $response['data'] ?? [];
                $newStatus = strtolower($data['status'] ?? $shipment->status);

                // Update shipment
                $shipment->update([
                    'status' => $newStatus,
                    'delivered_at' => ($newStatus === 'delivered') ? now() : $shipment->delivered_at,
                ]);

                // Update parent order
                $order = $shipment->order;
                if ($order) {
                    $order->update(['status' => $newStatus, 'delivery_status' => $newStatus]);
                    
                    // Log to timeline if status changed
                    if ($order->wasChanged('status')) {
                        $order->logStatus("Sync: Status updated to " . ucfirst($newStatus) . " from NimbusPost.");
                    }
                }

                // Sync tracking history
                if (isset($data['history']) && is_array($data['history'])) {
                    foreach ($data['history'] as $history) {
                        $shipment->trackings()->updateOrCreate(
                            [
                                'activity' => $history['activity'],
                                'event_at' => date('Y-m-d H:i:s', strtotime($history['date']))
                            ],
                            [
                                'status' => $history['status'] ?? $newStatus,
                                'location' => $history['location'] ?? null,
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
        // This can be expanded to create/update NDR records in a local table
        $response = $this->nimbus->getNDR();
        if ($response['status'] ?? false) {
            // Logic to store NDR records locally if needed
            $this->comment('NDR data fetched (Logic to store can be added).');
        }
    }
}
