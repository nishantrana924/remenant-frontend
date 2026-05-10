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
        $item = $this->service->getById($id);
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
        $data = $request->only(['status', 'delivery_status', 'tracking_id', 'courier_name', 'payment_status']);
        $this->service->update($id, $data);

        return response()->json([
            'success' => true,
            'message' => 'Order updated successfully',
            'order' => $this->service->getById($id)
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
}
