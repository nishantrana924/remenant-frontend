<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingLog;
use App\Models\Shipment;
use App\Services\NimbusPostService;
use Illuminate\Http\Request;

class LogisticsController extends Controller
{
    protected $nimbus;

    public function __construct(NimbusPostService $nimbus)
    {
        $this->nimbus = $nimbus;
    }

    public function logs()
    {
        $logs = ShippingLog::latest()->paginate(50);
        return view('admin.logistics.logs', compact('logs'));
    }

    public function ndr()
    {
        $response = $this->nimbus->getNDR();
        $items = $response['data'] ?? [];
        return view('admin.logistics.ndr', compact('items'));
    }

    public function ndrAction(Request $request)
    {
        $validated = $request->validate([
            'shipment_id' => 'required', // This is the AWB
            'action' => 'required', // re-attempt, change_address, change_phone
        ]);

        $payload = [
            'awb' => $validated['shipment_id'],
            'action' => $validated['action'],
            'action_data' => []
        ];

        if ($validated['action'] === 're-attempt') {
            $payload['action_data']['re_attempt_date'] = now()->format('Y-m-d');
        } elseif ($validated['action'] === 'change_address') {
            // In a real scenario, you'd get this from the request
            $payload['action_data'] = [
                'name' => 'Updated Name',
                'address_1' => 'Updated Address 1',
                'address_2' => 'Updated Address 2'
            ];
        }

        $response = $this->nimbus->ndrAction($payload);

        if ($response['status'] ?? false) {
            return response()->json(['success' => true, 'message' => 'Action submitted successfully.']);
        }

        return response()->json(['success' => false, 'message' => $response['message'] ?? 'Failed to process NDR action.'], 422);
    }
}
