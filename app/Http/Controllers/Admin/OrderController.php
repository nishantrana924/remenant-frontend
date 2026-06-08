<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Services\OrderService;
use App\Http\Requests\Admin\OrderRequest;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends BaseController
{
    protected $service;

    public function __construct(OrderService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        // Using paginate to avoid memory and timeout issues with large datasets
        $items = \App\Models\Order::with(['user', 'shipment', 'orderItems.product'])->latest()->paginate(50);
        return view('admin.orders.index', compact('items'));
    }

    public function show($id, \App\Services\NimbusPostService $nimbus)
    {
        $item = \App\Models\Order::with(['user', 'orderItems.product', 'timelines.user', 'shipment'])->findOrFail($id);
        
        $shippingCharges = null;
        if ($item->shipment && $item->shipment->billed_charge) {
            $shippingCharges = $item->shipment->billed_charge;
        }

        return view('admin.orders.show', compact('item', 'shippingCharges'));
    }

    public function create()
    {
        return view('admin.orders.create');
    }

    public function store(OrderRequest $request)
    {
        $this->service->create($request->validated());
        return redirect()->route('admin.orders.index')->with('success', 'Order created successfully.');
    }

    public function edit($id)
    {
        $item = $this->service->getById($id);
        return view('admin.orders.edit', compact('item'));
    }

    public function update(OrderRequest $request, $id)
    {
        $this->service->update($id, $request->validated());
        return redirect()->route('admin.orders.index')->with('success', 'Order updated successfully.');
    }

    public function destroy($id)
    {
        $this->service->delete($id);
        return redirect()->route('admin.orders.index')->with('success', 'Order deleted successfully.');
    }

    /**
     * AJAX status update for Shopify-style command center
     */
    public function updateStatus(Request $request, $id)
    {
        $data = $request->only(['status', 'delivery_status', 'tracking_id', 'courier_name', 'payment_status', 'message']);
        $order = \App\Models\Order::findOrFail($id);
        $order->update(collect($data)->except('message')->toArray());
        
        // If it's a manual shipping update, create/update the shipment record
        if (isset($data['tracking_id']) && $data['tracking_id'] != $order->getOriginal('tracking_id')) {
            \App\Models\Shipment::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'awb_number' => $data['tracking_id'],
                    'courier_name' => $data['courier_name'] ?? 'Manual',
                    'status' => $data['status'] ?? 'shipped',
                ]
            );
        }

        $order->logStatus($data['message'] ?? null);

        return response()->json([
            'success' => true,
            'message' => 'Order updated successfully',
            'order' => $order->load(['user', 'orderItems.product', 'timelines'])
        ]);
    }

    public function bulkUpdateStatus(Request $request)
    {
        $ids = $request->input('ids', []);
        $data = $request->only(['status', 'delivery_status', 'payment_status']);
        
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No orders selected.'], 400);
        }

        \App\Models\Order::whereIn('id', $ids)->update($data);

        return response()->json([
            'success' => true,
            'message' => count($ids) . ' orders updated successfully.'
        ]);
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No orders selected.'], 400);
        }

        \App\Models\Order::whereIn('id', $ids)->delete();

        return response()->json([
            'success' => true,
            'message' => count($ids) . ' orders deleted successfully.'
        ]);
    }

    public function fetchNimbusRates(Request $request, $id, \App\Services\NimbusPostService $nimbus)
    {
        $order = \App\Models\Order::findOrFail($id);
        
        $request->validate([
            'weight' => 'required|numeric|min:1',
            'length' => 'required|numeric|min:1',
            'breadth' => 'required|numeric|min:1',
            'height' => 'required|numeric|min:1',
        ]);

        $warehouse = \App\Models\Warehouse::where('is_default', true)->first();
        if (!$warehouse) {
            return response()->json(['success' => false, 'message' => 'Please configure a default warehouse first.'], 422);
        }

        // Validate warehouse has a real pincode
        if (empty($warehouse->pincode) || $warehouse->pincode === '000000' || $warehouse->pincode === 'N/A') {
            return response()->json([
                'success' => false,
                'message' => 'Default warehouse pincode is not set. Please edit the warehouse and fill in the correct pincode first.'
            ], 422);
        }

        // Validate order has destination pincode
        if (empty($order->pincode)) {
            return response()->json(['success' => false, 'message' => 'Customer pincode is missing in this order.'], 422);
        }

        $payload = [
            'origin'        => (string)$warehouse->pincode,
            'destination'   => (string)$order->pincode,
            'payment_type'  => 'prepaid',
            'order_amount'  => (float)$order->total_amount,
            'weight'        => (int)$request->weight,
            'length'        => (int)$request->length,
            'breadth'       => (int)$request->breadth,
            'height'        => (int)$request->height,
        ];

        $response = $nimbus->getRates($payload);

        if (isset($response['status']) && $response['status'] === true) {
            return response()->json(['success' => true, 'data' => $response['data']]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to fetch rates: ' . ($response['message'] ?? 'Check logs.'),
        ], 500);
    }

    public function shipToNimbusPost(Request $request, $id, \App\Services\NimbusPostService $nimbus)
    {
        $order = \App\Models\Order::with('orderItems.product')->findOrFail($id);
        
        if ($order->shipment) {
            return response()->json(['success' => false, 'message' => 'Shipment already exists for this order.'], 422);
        }

        $warehouse = \App\Models\Warehouse::where('is_default', true)->first();
        if (!$warehouse) {
            return response()->json(['success' => false, 'message' => 'Please configure a default warehouse first.'], 422);
        }

        $items = $order->orderItems->map(function ($item) {
            return [
                'name'  => $item->product->title ?? $item->product->name ?? 'Product',
                'qty'   => (string)$item->quantity,
                'price' => (string)$item->price,
                'sku'   => $item->product->sku ?? 'SKU-' . $item->product_id,
            ];
        })->toArray();

        // Validate mandatory fields before pushing
        if (empty($order->phone) || empty($order->address) || empty($order->city) || empty($order->state) || empty($order->pincode)) {
            return response()->json(['success' => false, 'message' => 'Customer address details (Phone, City, State, Pincode) are mandatory for NimbusPost.'], 422);
        }

        // Build full address (address + landmark if available)
        $fullAddress = trim($order->address . ($order->landmark ? ', ' . $order->landmark : ''));

        $courierId = $request->input('courier_id');

        // Debug: Log exactly what we received
        \Illuminate\Support\Facades\Log::info('NimbusPost Ship Request - courier_id received: ' . var_export($courierId, true), [
            'all_input' => $request->all()
        ]);

        if (empty($courierId)) {
            return response()->json([
                'success' => false,
                'message' => 'Courier not selected. Please click "Fetch Courier Rates" and select a courier first.'
            ], 422);
        }

        $payload = [
            // ── Order Info (TOP LEVEL per NimbusPost docs) ──
            'order_number'      => (string)$order->order_number,
            'payment_type'      => 'prepaid',
            'order_amount'      => (float)$order->total_amount,
            'courier_id'        => (int)$courierId,   // MUST be top-level

            // ── Package Dimensions ──
            'package_weight'    => (int)$request->input('weight', 500),
            'package_length'    => (int)$request->input('length', 10),
            'package_breadth'   => (int)$request->input('breadth', 10),
            'package_height'    => (int)$request->input('height', 10),

            'request_auto_pickup' => 0,

            // ── Consignee (Customer) ──
            'consignee' => [
                'name'    => $order->customer_name,
                'address' => $fullAddress,
                'city'    => $order->city,
                'state'   => $order->state,
                'pincode' => (string)$order->pincode,
                'phone'   => (string)preg_replace('/[^0-9]/', '', $order->phone),
            ],

            // ── Pickup (Warehouse — full details required) ──
            'pickup' => [
                'warehouse_name' => $warehouse->name,
                'name'           => $warehouse->contact_person ?? $warehouse->name,
                'address'        => $warehouse->address,
                'address_2'      => $warehouse->address_2 ?? '',
                'city'           => $warehouse->city,
                'state'          => $warehouse->state,
                'pincode'        => (string)$warehouse->pincode,
                'phone'          => (string)preg_replace('/[^0-9]/', '', $warehouse->phone ?? ''),
                'gst_number'     => $warehouse->gst_number ?? '',
            ],

            // ── Order Items ──
            'order_items' => array_map(function ($item) {
                return [
                    'name'  => $item['name'] ?: 'Product',
                    'qty'   => (int)$item['qty'],
                    'price' => (float)$item['price'],
                    'sku'   => $item['sku'] ?? 'SKU',
                ];
            }, $items),
        ];

        \Illuminate\Support\Facades\Log::info('NimbusPost Final Payload:', $payload);

        // Fetch rate before creating shipment to save the billed charge
        $billedCharge = null;
        try {
            $ratePayload = [
                'origin' => $warehouse->pincode ?? '110020',
                'destination' => $payload['consignee']['zipcode'],
                'payment_type' => $payload['payment_type'],
                'order_amount' => $payload['order_amount'],
                'weight' => $payload['package_weight'],
                'length' => $payload['package_length'],
                'breadth' => $payload['package_width'],
                'height' => $payload['package_height']
            ];
            $rates = $nimbus->getRates($ratePayload);
            if (isset($rates['status']) && $rates['status'] === true && !empty($rates['data'])) {
                foreach ($rates['data'] as $rate) {
                    if ($rate['id'] == $courierId) {
                        $billedCharge = $rate['total_charges'] ?? $rate['freight_charges'] ?? null;
                        break;
                    }
                }
            }
        } catch (\Exception $e) {}

        $response = $nimbus->createShipment($payload);

        if (isset($response['status']) && $response['status'] === true) {
            $data = $response['data'];
            
            // Create shipment record
            \App\Models\Shipment::create([
                'order_id' => $order->id,
                'nimbus_shipment_id' => $data['shipment_id'],
                'awb_number' => $data['awb_number'],
                'courier_name' => $data['courier_name'] ?? 'NimbusPost',
                'status' => $data['status'] ?? 'packed',
                'label_url' => $data['label'] ?? null,
                'billed_charge' => $billedCharge,
            ]);

            $order->update([
                'tracking_id' => $data['awb_number'],
                'courier_name' => $data['courier_name'] ?? 'NimbusPost',
                'status' => 'packed',
                'delivery_status' => 'packed',
            ]);

            $order->logStatus("Pushed to NimbusPost. AWB: " . $data['awb_number']);

            return response()->json([
                'success' => true, 
                'message' => 'Order pushed to NimbusPost! AWB: ' . $data['awb_number']
            ]);
        }

        return response()->json([
            'success' => false, 
            'message' => 'Failed to push to NimbusPost. ' . ($response['message'] ?? 'Check logs.'),
            'error' => $response
        ], 500);
    }

    public function bulkShipToNimbusPost(Request $request, \App\Services\NimbusPostService $nimbus)
    {
        set_time_limit(300); // Allow up to 5 minutes for bulk API requests
        $ids = $request->input('ids', []);
        $courierId = $request->input('courier_id');
        $packagingWeight = (int) $request->input('packaging_weight', 30);

        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No orders selected.'], 400);
        }

        if (!$courierId) {
            return response()->json(['success' => false, 'message' => 'Please select a courier.'], 400);
        }

        $orders = \App\Models\Order::with('orderItems.product')->whereIn('id', $ids)->get();
        
        $warehouse = \App\Models\Warehouse::where('is_default', true)->first();
        if (!$warehouse) {
            return response()->json(['success' => false, 'message' => 'Please configure a default warehouse first.'], 422);
        }

        $successCount = 0;
        $failedCount = 0;
        $errors = [];

        foreach ($orders as $order) {
            if ($order->shipment) {
                $failedCount++;
                $errors[] = "Order #{$order->id}: Already shipped.";
                continue;
            }

            if (!in_array($order->status, ['processing', 'packed'])) {
                $failedCount++;
                $errors[] = "Order #{$order->id}: Not approved yet.";
                continue;
            }

            if (empty($order->phone) || empty($order->address) || empty($order->city) || empty($order->state) || empty($order->pincode)) {
                $failedCount++;
                $errors[] = "Order #{$order->id}: Missing address details.";
                continue;
            }

            $dims = $order->calculated_dimensions;
            $items = $order->orderItems->map(function ($item) {
                return [
                    'name'  => $item->product->title ?? $item->product->name ?? 'Product',
                    'qty'   => (string)$item->quantity,
                    'price' => (string)$item->price,
                    'sku'   => $item->product->sku ?? 'SKU-' . $item->product_id,
                ];
            })->toArray();

            $fullAddress = trim($order->address . ($order->landmark ? ', ' . $order->landmark : ''));

            // Business logic requirement: total packaging weight is added to dynamic product weight sum.
            $finalWeight = $dims['weight'] + $packagingWeight;

            $payload = [
                'order_number'      => (string)$order->order_number,
                'payment_type'      => 'prepaid',
                'order_amount'      => (float)$order->total_amount,
                'courier_id'        => (int)$courierId,
                'package_weight'    => (int)$finalWeight,
                'package_length'    => (int)$dims['length'],
                'package_breadth'   => (int)$dims['breadth'],
                'package_height'    => (int)$dims['height'],
                'request_auto_pickup' => 0,
                'consignee' => [
                    'name'    => $order->customer_name,
                    'address' => $fullAddress,
                    'city'    => $order->city,
                    'state'   => $order->state,
                    'pincode' => (string)$order->pincode,
                    'phone'   => (string)preg_replace('/[^0-9]/', '', $order->phone),
                ],
                'pickup' => [
                    'warehouse_name' => $warehouse->name,
                    'name'           => $warehouse->contact_person ?? $warehouse->name,
                    'address'        => $warehouse->address,
                    'address_2'      => $warehouse->address_2 ?? '',
                    'city'           => $warehouse->city,
                    'state'          => $warehouse->state,
                    'pincode'        => (string)$warehouse->pincode,
                    'phone'          => (string)preg_replace('/[^0-9]/', '', $warehouse->phone ?? ''),
                ],
                'order_items' => array_map(function ($item) {
                    return [
                        'name'  => $item['name'] ?: 'Product',
                        'qty'   => (int)$item['qty'],
                        'price' => (float)$item['price'],
                        'sku'   => $item['sku'] ?? 'SKU',
                    ];
                }, $items),
            ];

            // Fetch rate before creating shipment to save the billed charge
            $billedCharge = null;
            try {
                $ratePayload = [
                    'origin' => $warehouse->pincode ?? '110020',
                    'destination' => $payload['consignee']['zipcode'],
                    'payment_type' => $payload['payment_type'],
                    'order_amount' => $payload['order_amount'],
                    'weight' => $payload['package_weight'],
                    'length' => $payload['package_length'],
                    'breadth' => $payload['package_width'],
                    'height' => $payload['package_height']
                ];
                $rates = $nimbus->getRates($ratePayload);
                if (isset($rates['status']) && $rates['status'] === true && !empty($rates['data'])) {
                    foreach ($rates['data'] as $rate) {
                        if ($rate['id'] == $courierId) {
                            $billedCharge = $rate['total_charges'] ?? $rate['freight_charges'] ?? null;
                            break;
                        }
                    }
                }
            } catch (\Exception $e) {}

            $response = $nimbus->createShipment($payload);

            if (isset($response['status']) && $response['status'] === true) {
                $data = $response['data'];
                \App\Models\Shipment::create([
                    'order_id' => $order->id,
                    'nimbus_shipment_id' => $data['shipment_id'],
                    'awb_number' => $data['awb_number'],
                    'courier_name' => $data['courier_name'] ?? 'NimbusPost',
                    'status' => $data['status'] ?? 'packed',
                    'label_url' => $data['label'] ?? null,
                    'billed_charge' => $billedCharge,
                ]);

                $order->update([
                    'tracking_id' => $data['awb_number'],
                    'courier_name' => $data['courier_name'] ?? 'NimbusPost',
                    'status' => 'packed',
                    'delivery_status' => 'packed',
                ]);
                $order->logStatus("Bulk pushed to NimbusPost. AWB: " . $data['awb_number']);
                $successCount++;
            } else {
                $failedCount++;
                $errors[] = "Order #{$order->id}: " . ($response['message'] ?? 'API Error');
            }
        }

        return response()->json([
            'success' => true,
            'total' => count($ids),
            'successful' => $successCount,
            'failed' => $failedCount,
            'errors' => $errors
        ]);
    }

    public function bulkCancelNimbusPost(Request $request, \App\Services\NimbusPostService $nimbus)
    {
        $ids = $request->input('ids', []);
        
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No orders selected.'], 400);
        }

        $orders = \App\Models\Order::with('shipment')->whereIn('id', $ids)->get();
        
        $successCount = 0;
        $failedCount = 0;
        $errors = [];

        foreach ($orders as $order) {
            if (!$order->shipment || !$order->shipment->nimbus_shipment_id) {
                $failedCount++;
                $errors[] = "Order #{$order->id}: No active shipment.";
                continue;
            }

            $response = $nimbus->cancelShipment($order->shipment->nimbus_shipment_id, $order->shipment->awb_number);

            if (isset($response['status']) && $response['status'] === true) {
                $order->update([
                    'status' => 'processing',
                    'tracking_id' => null,
                    'courier_name' => null,
                    'delivery_status' => 'pending',
                ]);
                $order->shipment->delete();
                $order->logStatus("Bulk NimbusPost shipment cancelled.");
                
                \Illuminate\Support\Facades\Cache::forget('nimbus_charges_' . $order->shipment->nimbus_shipment_id);
                \Illuminate\Support\Facades\Cache::forget('nimbus_tracking_' . $order->shipment->nimbus_shipment_id);
                
                $successCount++;
            } else {
                $failedCount++;
                $errors[] = "Order #{$order->id}: " . ($response['message'] ?? 'API Error');
            }
        }

        return response()->json([
            'success' => true,
            'total' => count($ids),
            'successful' => $successCount,
            'failed' => $failedCount,
            'errors' => $errors
        ]);
    }

    public function fetchCouriers(\App\Services\NimbusPostService $nimbus)
    {
        $response = $nimbus->getCouriers();
        if (isset($response['status']) && $response['status'] === true) {
            return response()->json(['success' => true, 'couriers' => $response['data']]);
        }
        return response()->json(['success' => false, 'message' => 'Failed to fetch couriers'], 500);
    }

    public function bulkPackingSlips(Request $request, \App\Services\NimbusPostService $nimbus)
    {
        $ids = explode(',', $request->query('ids', ''));
        if (empty($ids) || $ids[0] === '') {
            return redirect()->back()->with('error', 'No orders provided for packing slips.');
        }

        $shipments = \App\Models\Shipment::whereIn('order_id', $ids)->get();
        $awbs = $shipments->pluck('awb_number')->filter()->toArray();

        if (empty($awbs)) {
            return redirect()->back()->with('error', 'No AWBs found for the selected orders.');
        }

        $response = $nimbus->generateManifest($awbs);

        if (isset($response['status']) && $response['status'] === true && !empty($response['data'])) {
            // Nimbus returns a single merged PDF URL for the manifest
            return redirect()->away($response['data']);
        }

        return redirect()->back()->with('error', 'Failed to generate manifest from NimbusPost: ' . ($response['message'] ?? 'Unknown Error'));
    }

    public function bulkShippingLabels(Request $request)
    {
        $ids = explode(',', $request->query('ids', ''));
        if (empty($ids) || $ids[0] === '') {
            return redirect()->back()->with('error', 'No orders provided for labels.');
        }

        $shipments = \App\Models\Shipment::whereIn('order_id', $ids)->whereNotNull('label_url')->get();
        
        if ($shipments->isEmpty()) {
            return redirect()->back()->with('error', 'No valid shipping labels found in database for the selected orders.');
        }

        if (!class_exists('ZipArchive')) {
            // Fallback: If ZipArchive is missing, generate a simple HTML page that auto-downloads all PDFs via JS
            $links = $shipments->pluck('label_url')->toArray();
            return response(view('admin.orders.auto-download-labels', compact('links')));
        }

        $zipFile = storage_path('app/public/shipping-labels-' . time() . '.zip');
        
        // Ensure directory exists
        if (!file_exists(dirname($zipFile))) {
            mkdir(dirname($zipFile), 0755, true);
        }

        $zip = new \ZipArchive();
        if ($zip->open($zipFile, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
            foreach ($shipments as $shipment) {
                try {
                    // Use Laravel HTTP client to avoid allow_url_fopen restrictions
                    $response = \Illuminate\Support\Facades\Http::timeout(15)->get($shipment->label_url);
                    if ($response->successful()) {
                        $filename = 'Label_' . $shipment->awb_number . '_Order_' . $shipment->order_id . '.pdf';
                        $zip->addFromString($filename, $response->body());
                    } else {
                        \Illuminate\Support\Facades\Log::error("Failed to download label for AWB {$shipment->awb_number}, HTTP status: " . $response->status());
                    }
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error("Exception downloading label for AWB {$shipment->awb_number}: " . $e->getMessage());
                }
            }
            $zip->close();
            return response()->download($zipFile)->deleteFileAfterSend(true);
        }

        return redirect()->back()->with('error', 'Failed to generate ZIP archive of shipping labels.');
    }

    public function generateInvoice($id)
    {
        $order = \App\Models\Order::with(['user', 'orderItems.product'])->findOrFail($id);
        return view('admin.orders.invoice', compact('order'));
    }

    public function generatePackingSlip($id)
    {
        $order = \App\Models\Order::with(['user', 'orderItems.product'])->findOrFail($id);
        return view('admin.orders.packing-slip', compact('order'));
    }

    public function cancelNimbusPost($id, \App\Services\NimbusPostService $nimbus)
    {
        $order = Order::findOrFail($id);
        $shipment = $order->shipment;

        if (!$shipment || !$shipment->nimbus_shipment_id) {
            return response()->json(['success' => false, 'message' => 'No active NimbusPost shipment found for this order.'], 422);
        }

        $response = $nimbus->cancelShipment($shipment->nimbus_shipment_id, $shipment->awb_number);

        if (isset($response['status']) && $response['status'] === true) {
            $shipment->delete();
            $order->update([
                'status' => 'processing', // Reset status to processing
                'tracking_id' => null,
                'courier_name' => null,
                'delivery_status' => 'pending'
            ]);
            
            \Illuminate\Support\Facades\Cache::forget('nimbus_charges_' . $order->id);
            \Illuminate\Support\Facades\Cache::forget('nimbus_charges_' . $shipment->nimbus_shipment_id);
            
            return response()->json(['success' => true, 'message' => 'Shipment cancelled successfully on NimbusPost.']);
        }

        return response()->json(['success' => false, 'message' => 'Failed to cancel shipment: ' . ($response['message'] ?? 'Unknown Error')], 500);
    }

    public function generateNimbusLabel($id, \App\Services\NimbusPostService $nimbus)
    {
        $order = Order::findOrFail($id);
        $shipment = $order->shipment;

        if (!$shipment || !$shipment->nimbus_shipment_id) {
            return redirect()->back()->with('error', 'No active NimbusPost shipment found.');
        }

        // ✅ Step 1: Fetch fresh label URL from NimbusPost API (S3 links expire after a while)
        $response = $nimbus->generateLabel($shipment->nimbus_shipment_id);

        if (isset($response['status']) && $response['status'] === true && !empty($response['data'])) {
            // Save/update the fresh label URL to DB
            $shipment->update(['label_url' => $response['data']]);
            return redirect()->away($response['data']);
        }

        // ✅ Step 2: Fallback - if API call failed, try the cached label_url from DB as a last resort
        if (!empty($shipment->label_url)) {
            return redirect()->away($shipment->label_url);
        }

        return redirect()->back()->with('error', 'Failed to generate label: ' . ($response['message'] ?? 'Unknown Error'));
    }

    public function bulkPickup(Request $request, \App\Services\NimbusPostService $nimbus)
    {
        $ids = $request->ids;
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No orders selected.'], 422);
        }

        // Support both order_id (from local list) and nimbus_shipment_id (from live list)
        $shipments = \App\Models\Shipment::whereIn('order_id', $ids)
            ->orWhereIn('nimbus_shipment_id', $ids)
            ->whereNotNull('nimbus_shipment_id')
            ->get();

        if ($shipments->isEmpty()) {
            return response()->json([
                'success' => false, 
                'message' => 'None of the selected orders have been pushed to the courier yet. You must generate shipments before scheduling a pickup.'
            ], 422);
        }

        $nimbusOrderIds = $shipments->pluck('nimbus_shipment_id')->toArray();

        $response = $nimbus->requestPickup($nimbusOrderIds);

        if (isset($response['status']) && $response['status'] === true) {
            return response()->json(['success' => true, 'message' => 'Pickup requested successfully.']);
        }

        return response()->json(['success' => false, 'message' => 'Failed to request pickup: ' . ($response['message'] ?? 'Unknown Error')], 500);
    }

    public function approveCancellation($id)
    {
        $order = Order::findOrFail($id);

        if ($order->status !== 'cancellation_requested') {
            return response()->json(['success' => false, 'message' => 'Order is not pending cancellation.'], 400);
        }

        \DB::transaction(function () use ($order) {
            // Lock order
            $order = Order::where('id', $order->id)->lockForUpdate()->first();

            $order->status = 'cancelled';
            $order->delivery_status = 'cancelled';
            $order->refund_status = 'pending';
            $order->cancelled_at = now();
            $order->cancelled_by = auth()->user()->name . ' (Admin)';
            $order->save();

            // Log timeline
            $order->logStatus("Cancellation Approved by Admin.");
            
            // Dispatch refund job
            \App\Jobs\ProcessOrderRefundJob::dispatch($order->id);
        });

        return response()->json([
            'success' => true,
            'message' => 'Cancellation approved successfully.'
        ]);
    }

    public function rejectCancellation($id)
    {
        $order = Order::findOrFail($id);

        if ($order->status !== 'cancellation_requested') {
            return response()->json(['success' => false, 'message' => 'Order is not pending cancellation.'], 400);
        }

        // Revert back to packed
        $order->status = 'packed';
        $order->delivery_status = 'packed';
        $order->cancellation_reason = null;
        $order->save();

        $order->logStatus("Cancellation Rejected by Admin. Order resumed.");

        return response()->json([
            'success' => true,
            'message' => 'Cancellation rejected successfully.'
        ]);
    }
}
