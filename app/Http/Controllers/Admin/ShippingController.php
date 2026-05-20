<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Shipment;
use App\Models\Warehouse;
use App\Services\NimbusPostService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ShippingController extends Controller
{
    protected $nimbus;

    public function __construct(NimbusPostService $nimbus)
    {
        $this->nimbus = $nimbus;
    }

    /**
     * Logistics Dashboard Overview
     */
    public function dashboard()
    {
        $stats = [
            'total_shipments' => Shipment::count(),
            'pending' => Shipment::where('status', 'pending')->count(),
            'in_transit' => Shipment::where('status', 'shipped')->count(),
            'delivered' => Shipment::where('status', 'delivered')->count(),
            'rto' => Shipment::where('status', 'rto')->count(),
            'total_revenue' => Order::where('payment_status', 'paid')->sum('total_amount'),
            'cod_collection' => Order::where('payment_method', 'cod')->sum('total_amount'),
        ];

        $recent_shipments = Shipment::with('order')->latest()->limit(10)->get();
        
        $couriersResponse = $this->nimbus->getCouriers();
        $active_couriers = $couriersResponse['data'] ?? [];

        return view('admin.logistics.dashboard', compact('stats', 'recent_shipments', 'active_couriers'));
    }

    /**
     * Order Management for Shipping
     */
    public function index(Request $request)
    {
        $query = Order::with('shipment');

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $items = $query->latest()->paginate(15);
        
        $stats = [
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'packed' => Order::where('status', 'packed')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
        ];

        return view('admin.logistics.index', compact('items', 'stats'));
    }

    /**
     * Rate Calculator API
     */
    public function calculateRates(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'origin_pincode' => 'required',
            'destination_pincode' => 'required',
            'weight' => 'required|numeric',
            'payment_type' => 'required',
            'order_amount' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please fill all fields correctly.',
                'errors' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();
        
        // Map keys to what NimbusPost expects
        $nimbusPayload = [
            'origin' => $validated['origin_pincode'],
            'destination' => $validated['destination_pincode'],
            'weight' => $validated['weight'],
            'payment_type' => $validated['payment_type'],
            'order_amount' => $validated['order_amount'] ?? 1000,
        ];

        $response = $this->nimbus->getRates($nimbusPayload);

        if ($response['status'] ?? false) {
            return response()->json([
                'success' => true,
                'data' => $response['data']
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $response['message'] ?? 'Failed to fetch rates from NimbusPost'
        ], 422);
    }

    /**
     * Create Shipment and Generate AWB
     */
    public function createShipment(Request $request, $orderId)
    {
        $order = Order::with('orderItems.product')->findOrFail($orderId);
        
        if ($order->shipment) {
            return response()->json(['success' => false, 'message' => 'Shipment already exists for this order.'], 422);
        }

        $warehouse = Warehouse::where('is_default', true)->first();
        if (!$warehouse) {
            return response()->json(['success' => false, 'message' => 'Please configure a default warehouse first.'], 422);
        }

        $items = $order->orderItems->map(function ($item) {
            return [
                'name' => $item->product->name,
                'qty' => (string)$item->quantity,
                'price' => (string)$item->price,
                'sku' => $item->product->sku ?? 'SKU-' . $item->product_id,
            ];
        })->toArray();

        $payload = [
            'order' => [
                'order_number' => (string)$order->order_number,
                'payment_type' => $order->payment_method === 'cod' ? 'cod' : 'prepaid',
                'total' => (float)$order->total_amount,
            ],
            'consignee' => [
                'name' => $order->customer_name,
                'address' => trim($order->address . ' ' . ($order->address_2 ?? '')),
                'city' => $order->city,
                'state' => $order->state,
                'pincode' => (string)$order->pincode,
                'phone' => (string)preg_replace('/[^0-9]/', '', $order->phone),
            ],
            'pickup_warehouse_id' => (string)$warehouse->nimbus_id,
            'package_weight' => (int)($request->weight ?? 500),
            'package_length' => (int)($request->length ?? 10),
            'package_breadth' => (int)($request->breadth ?? 10),
            'package_height' => (int)($request->height ?? 10),
            'order_items' => $items,
        ];

        $response = $this->nimbus->createShipment($payload);

        if ($response['status'] ?? false) {
            $data = $response['data'];
            
            $shipment = Shipment::create([
                'order_id' => $order->id,
                'nimbus_shipment_id' => $data['shipment_id'],
                'awb_number' => $data['awb_number'],
                'courier_name' => $data['courier_name'] ?? 'NimbusPost',
                'status' => 'packed',
                'label_url' => $data['label'] ?? null,
            ]);

            $order->update(['status' => 'packed', 'delivery_status' => 'packed']);
            $order->logStatus("Shipment created via NimbusPost. AWB: " . $data['awb_number']);

            return response()->json([
                'success' => true,
                'message' => 'Shipment created successfully! AWB: ' . $data['awb_number'],
                'shipment' => $shipment
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $response['message'] ?? 'Failed to create shipment'
        ], 500);
    }

    /**
     * Cancel Shipment
     */
    public function cancelShipment($id)
    {
        $shipment = Shipment::findOrFail($id);
        
        $response = $this->nimbus->cancelShipment($shipment->awb_number);

        if ($response['status'] ?? false) {
            $shipment->update(['status' => 'cancelled']);
            $shipment->order->update(['status' => 'cancelled']);
            $shipment->order->logStatus("Shipment cancelled via NimbusPost.");

            return response()->json(['success' => true, 'message' => 'Shipment cancelled successfully.']);
        }

        return response()->json(['success' => false, 'message' => $response['message'] ?? 'Failed to cancel shipment'], 422);
    }

    /**
     * Download Bulk Labels
     */
    public function bulkLabels(Request $request)
    {
        $ids = $request->ids;
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No shipments selected.'], 422);
        }

        $response = $this->nimbus->generateLabel($ids);

        if (isset($response['status']) && $response['status'] === true && !empty($response['data'])) {
            return response()->json([
                'success' => true,
                'url' => $response['data']
            ]);
        }

        return response()->json(['success' => false, 'message' => $response['message'] ?? 'Failed to generate bulk labels.'], 500);
    }

    /**
     * Track Shipment
     */
    public function track($awb)
    {
        $response = $this->nimbus->getTracking($awb);

        if ($response['status'] ?? false) {
            return response()->json([
                'success' => true,
                'data' => $response['data']
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Tracking data unavailable'], 404);
    }

    /**
     * Manual Sync All Data
     */
    public function syncAll()
    {
        try {
            \Illuminate\Support\Facades\Artisan::call('nimbus:sync');
            return redirect()->back()->with('success', 'Full logistics synchronization completed successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Sync failed: ' . $e->getMessage());
        }
    }

    /**
     * Get All Shipments from NimbusPost (Live List)
     */
    public function allShipments(Request $request)
    {
        $params = [
            'page' => $request->page ?? 1,
            'per_page' => $request->per_page ?? 20,
            'sort' => $request->sort ?? 'DESC',
            'sort_by' => $request->sort_by ?? 'id',
            'from' => $request->from,
            'to' => $request->to,
        ];

        $response = $this->nimbus->getAllShipments(array_filter($params));

        $shipments = [];
        $pagination = null;

        if ($response['status'] ?? false) {
            $shipments = $response['data'];
            // Nimbus usually returns pagination in the response metadata
            $pagination = $response['meta'] ?? null;
        }

        return view('admin.logistics.shipments', compact('shipments', 'pagination'));
    }

    /**
     * Get Single Shipment Details (AJAX)
     */
    public function showShipment($id)
    {
        $response = $this->nimbus->getShipmentDetails($id);
        
        if ($response['status'] ?? false) {
            return response()->json([
                'success' => true,
                'data' => $response['data']
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Details not found'], 404);
    }
}
