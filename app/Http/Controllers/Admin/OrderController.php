<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Services\OrderService;
use App\Http\Requests\Admin\OrderRequest;
use Illuminate\Http\Request;

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

    public function shipToShiprocket($id, \App\Services\ShiprocketService $shiprocket)
    {
        $order = \App\Models\Order::with('orderItems.product')->findOrFail($id);
        
        $response = $shiprocket->createOrder($order);

        if (isset($response['shipment_id'])) {
            $order->update([
                'tracking_id' => $response['shipment_id'],
                'courier_name' => $response['courier_name'] ?? 'Shiprocket',
                'delivery_status' => 'packed',
                'tracking_url' => $response['tracking_url'] ?? null
            ]);

            $order->logStatus("Pushed to Shiprocket. Shipment ID: " . $response['shipment_id']);

            return response()->json([
                'success' => true, 
                'message' => 'Order pushed to Shiprocket! Shipment ID: ' . $response['shipment_id']
            ]);
        }

        return response()->json([
            'success' => false, 
            'message' => 'Failed to push to Shiprocket. Check logs.',
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
}
