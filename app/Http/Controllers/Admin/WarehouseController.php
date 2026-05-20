<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use App\Services\NimbusPostService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WarehouseController extends Controller
{
    protected $nimbus;

    public function __construct(NimbusPostService $nimbus)
    {
        $this->nimbus = $nimbus;
    }

    public function index()
    {
        $items = Warehouse::latest()->get();
        return view('admin.logistics.warehouses.index', compact('items'));
    }

    public function create()
    {
        return view('admin.logistics.warehouses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'contact_person' => 'required|string|max:200',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'address_2' => 'nullable|string|max:200',
            'pincode' => 'required|string|max:10',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'gst_number' => 'nullable|string|max:15',
            'is_default' => 'nullable|boolean',
        ]);

        // 1. Prepare data for NimbusPost
        $nimbusData = [
            'name' => $validated['name'],
            'contact_name' => $validated['contact_person'],
            'phone' => (int)preg_replace('/[^0-9]/', '', $validated['phone']),
            'address_1' => $validated['address'],
            'address_2' => $validated['address_2'] ?? '',
            'city' => $validated['city'],
            'state' => $validated['state'],
            'zip' => (int)$validated['pincode'],
            'gst_number' => $validated['gst_number'] ?? '',
        ];

        // 2. Push to NimbusPost
        $response = $this->nimbus->createWarehouse($nimbusData);

        if (!($response['status'] ?? false)) {
            return redirect()->back()->withInput()->with('error', 'Failed to create warehouse on NimbusPost: ' . ($response['message'] ?? 'Unknown Error'));
        }

        // 3. Save locally with the new Nimbus ID
        $validated['nimbus_id'] = $response['data']['id'] ?? null;
        $validated['contact_name'] = $validated['contact_person'];

        if ($request->is_default) {
            Warehouse::where('is_default', true)->update(['is_default' => false]);
        }

        Warehouse::create($validated);

        return redirect()->route('admin.logistics.warehouses.index')->with('success', 'Warehouse created successfully locally and on NimbusPost.');
    }

    public function edit($id)
    {
        $warehouse = Warehouse::findOrFail($id);
        return view('admin.logistics.warehouses.edit', compact('warehouse'));
    }

    public function update(Request $request, $id)
    {
        $warehouse = Warehouse::findOrFail($id);
        
        $validated = $request->validate([
            'nimbus_id' => 'nullable|string',
            'name' => 'required|string|max:100',
            'contact_person' => 'nullable|string|max:200',
            'contact_name' => 'nullable|string|max:200',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'address_2' => 'nullable|string|max:200',
            'pincode' => 'nullable|string|max:10',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'gst_number' => 'nullable|string|max:15',
            'is_default' => 'nullable|boolean',
        ]);

        if ($request->is_default) {
            Warehouse::where('id', '!=', $id)->update(['is_default' => false]);
        }

        // Fill both if one is provided
        if (isset($validated['contact_person'])) $validated['contact_name'] = $validated['contact_person'];
        if (isset($validated['contact_name'])) $validated['contact_person'] = $validated['contact_name'];

        // 1. If it has a Nimbus ID, update on Nimbus too
        if ($warehouse->nimbus_id) {
            $nimbusData = [
                'name' => $validated['name'],
                'contact_name' => $validated['contact_name'] ?? $warehouse->contact_name,
                'phone' => (int)preg_replace('/[^0-9]/', '', ($validated['phone'] ?? $warehouse->phone)),
                'address_1' => $validated['address'] ?? $warehouse->address,
                'address_2' => $validated['address_2'] ?? $warehouse->address_2,
                'city' => $validated['city'] ?? $warehouse->city,
                'state' => $validated['state'] ?? $warehouse->state,
                'zip' => (int)($validated['pincode'] ?? $warehouse->pincode),
                'gst_number' => $validated['gst_number'] ?? $warehouse->gst_number,
            ];

            $response = $this->nimbus->updateWarehouse($warehouse->nimbus_id, $nimbusData);

            if (!($response['status'] ?? false)) {
                // Nimbus API is broken for warehouse updates, log it and proceed anyway
                \Illuminate\Support\Facades\Log::warning('NimbusPost Warehouse Update Failed', ['error' => $response]);
                $nimbusWarning = true;
            }
        }

        $warehouse->update($validated);

        if (isset($nimbusWarning) && $nimbusWarning) {
            return redirect()->route('admin.logistics.warehouses.index')
                ->with('error', 'Updated locally, but Nimbus API failed. Please also update this warehouse in your NimbusPost Dashboard.');
        }

        return redirect()->route('admin.logistics.warehouses.index')->with('success', 'Warehouse updated successfully.');
    }

    public function setDefault($id)
    {
        Warehouse::where('id', '!=', $id)->update(['is_default' => false]);
        Warehouse::findOrFail($id)->update(['is_default' => true]);
        return redirect()->back()->with('success', 'Default warehouse updated.');
    }

    public function syncFromNimbus()
    {
        $response = $this->nimbus->getWarehouses();
        
        Log::info('Nimbus Warehouse Sync Response:', (array)$response);
        
        $status = $response['status'] ?? false;
        // Handle cases where status might be 1 or '1'
        if ($status === 1 || $status === '1' || $status === true || $status === 'true') {
            $remoteWarehouses = $response['data'] ?? [];
            
            // If data is empty but response is successful, maybe it's nested differently or empty
            if (empty($remoteWarehouses)) {
                return redirect()->back()->with('info', 'Nimbus returned success but no warehouses were found in your account.');
            }
            
            foreach ($remoteWarehouses as $remote) {
                $warehouse = Warehouse::where('nimbus_id', $remote['id'])->first();
                
                if ($warehouse) {
                    // Update only fields provided by the list API to prevent data loss
                    $warehouse->update([
                        'name' => $remote['name'] ?? $warehouse->name,
                    ]);
                } else {
                    // Create new warehouse with fallback values if API doesn't provide them
                    Warehouse::create([
                        'nimbus_id' => $remote['id'],
                        'name' => $remote['name'] ?? 'Nimbus Warehouse',
                        'contact_person' => $remote['contact_name'] ?? 'N/A',
                        'phone' => $remote['phone'] ?? '0000000000',
                        'address' => $remote['address_1'] ?? 'N/A',
                        'address_2' => $remote['address_2'] ?? '',
                        'pincode' => $remote['zip'] ?? '000000',
                        'city' => $remote['city'] ?? 'N/A',
                        'state' => $remote['state'] ?? 'N/A',
                        'gst_number' => $remote['gst_number'] ?? '',
                        'is_default' => ($remote['is_default'] ?? false) || ($remote['active'] == '1' && Warehouse::where('is_default', true)->count() == 0),
                    ]);
                }
            }

            return redirect()->back()->with('success', count($remoteWarehouses) . ' warehouses synced successfully.');
        }

        return redirect()->back()->with('error', $response['message'] ?? 'Failed to sync warehouses. Check API Logs.');
    }

    public function destroy($id)
    {
        Warehouse::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Warehouse deleted.');
    }
}
