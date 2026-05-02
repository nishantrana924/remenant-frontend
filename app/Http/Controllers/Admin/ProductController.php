<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Services\ProductService;
use App\Http\Requests\Admin\ProductRequest;
use Illuminate\Http\Request;

class ProductController extends BaseController
{
    protected $service;

    public function __construct(ProductService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $items = $this->service->getAll();
        return view('admin.products.index', compact('items'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(ProductRequest $request)
    {
        $this->service->create($request->validated());
        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function edit($id)
    {
        $item = $this->service->getById($id);
        return view('admin.products.edit', compact('item'));
    }

    public function update(ProductRequest $request, $id)
    {
        $this->service->update($id, $request->validated());
        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy($id)
    {
        $this->service->delete($id);
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
}
