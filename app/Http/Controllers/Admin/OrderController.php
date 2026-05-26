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
        $items = $this->service->getAll();
        return view('admin.orders.index', compact('items'));
    }

    public function show($id)
    {
        $item = \App\Models\Order::with(['user', 'orderItems.product', 'timelines.user'])->findOrFail($id);
        return view('admin.orders.show', compact('item'));
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
            'payment_type'  => $order->payment_method === 'cod' ? 'cod' : 'prepaid',
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
            'payment_type'      => $order->payment_method === 'cod' ? 'cod' : 'prepaid',
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
            $order->update(['status' => 'packed']); // Reset status to packed
            
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

        // ✅ Step 1: If label URL already exists in DB, use it directly (no API call needed)
        if (!empty($shipment->label_url)) {
            return redirect()->away($shipment->label_url);
        }

        // ✅ Step 2: Fallback - fetch label from NimbusPost API
        $response = $nimbus->generateLabel($shipment->nimbus_shipment_id);

        if (isset($response['status']) && $response['status'] === true && !empty($response['data'])) {
            // Save the label URL to DB so we don't need to call API again
            $shipment->update(['label_url' => $response['data']]);
            return redirect()->away($response['data']);
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
            // If not found in our DB, they might be direct Nimbus IDs from the live list UI
            $nimbusOrderIds = $ids;
        } else {
            $nimbusOrderIds = $shipments->pluck('nimbus_shipment_id')->toArray();
        }

        $response = $nimbus->requestPickup($nimbusOrderIds);

        if (isset($response['status']) && $response['status'] === true) {
            return response()->json(['success' => true, 'message' => 'Pickup requested successfully.']);
        }

        return response()->json(['success' => false, 'message' => 'Failed to request pickup: ' . ($response['message'] ?? 'Unknown Error')], 500);
    }
}
