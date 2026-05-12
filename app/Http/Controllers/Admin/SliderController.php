<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Services\SliderService;
use App\Http\Requests\Admin\SliderRequest;
use Illuminate\Http\Request;

class SliderController extends BaseController
{
    protected $service;

    public function __construct(SliderService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $items = $this->service->getAll();
        return view('admin.sliders.index', compact('items'));
    }

    public function create()
    {
        return view('admin.sliders.create');
    }

    public function store(SliderRequest $request)
    {
        $this->service->create($request->validated());
        return redirect()->route('admin.sliders.index')->with('success', 'Slider created successfully.');
    }

    public function edit($id)
    {
        $item = $this->service->getById($id);
        return view('admin.sliders.edit', compact('item'));
    }

    public function update(SliderRequest $request, $id)
    {
        $this->service->update($id, $request->validated());
        return redirect()->route('admin.sliders.index')->with('success', 'Slider updated successfully.');
    }

    public function destroy($id)
    {
        $this->service->delete($id);
        return redirect()->route('admin.sliders.index')->with('success', 'Slider deleted successfully.');
    }

    public function toggleStatus($id)
    {
        $item = $this->service->getById($id);
        
        // Ensure at least one slider remains active
        if ($item->status) {
            $activeCount = \App\Models\Slider::where('status', true)->count();
            if ($activeCount <= 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'At least one slider must remain active.'
                ], 422);
            }
        }

        $this->service->update($id, ['status' => !$item->status]);
        
        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully.'
        ]);
    }
}
