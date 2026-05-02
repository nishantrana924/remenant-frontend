<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Services\OrderItemService;
use App\Http\Requests\Admin\OrderItemRequest;
use Illuminate\Http\Request;

class OrderItemController extends BaseController
{
    protected $service;

    public function __construct(OrderItemService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $orderItems = $this->service->getAll();
        return view('admin.orderItems.index', compact('orderItems'));
    }

    public function create()
    {
        return view('admin.orderItems.create');
    }

    public function store(OrderItemRequest $request)
    {
        $this->service->create($request->validated());
        return redirect()->route('admin.orderItems.index')->with('success', 'OrderItem created successfully.');
    }

    public function edit($id)
    {
        $orderItem = $this->service->getById($id);
        return view('admin.orderItems.edit', compact('orderItem'));
    }

    public function update(OrderItemRequest $request, $id)
    {
        $this->service->update($id, $request->validated());
        return redirect()->route('admin.orderItems.index')->with('success', 'OrderItem updated successfully.');
    }

    public function destroy($id)
    {
        $this->service->delete($id);
        return redirect()->route('admin.orderItems.index')->with('success', 'OrderItem deleted successfully.');
    }
}
