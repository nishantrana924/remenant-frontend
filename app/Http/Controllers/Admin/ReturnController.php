<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Shipment;
use App\Models\Warehouse;
use App\Services\NimbusPostService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReturnController extends Controller
{
    /**
     * Return shipping charge deducted from refund (configurable).
     */
    const RETURN_SHIPPING_CHARGE = 100;

    /**
     * Approve a return request:
     * 1. Create NimbusPost reverse pickup shipment
     * 2. Trigger Razorpay refund (order total - return shipping charge)
     * 3. Update order return_status → approved
     */
    public function approveReturn(Request $request, $id, NimbusPostService $nimbus)
    {
        try {
            $order = Order::with(['orderItems.product', 'shipment'])->findOrFail($id);

            if ($order->return_status !== 'requested') {
                return response()->json([
                    'success' => false,
                    'message' => 'This order does not have a pending return request.'
                ], 422);
            }

            $warehouse = Warehouse::where('is_default', true)->first();
            if (!$warehouse) {
                return response()->json([
                    'success' => false,
                    'message' => 'No default warehouse configured.'
                ], 422);
            }

            $returnShippingCharge = floatval($request->input('return_shipping_charge', self::RETURN_SHIPPING_CHARGE));
            $netRefund = max(0, $order->total_amount - $returnShippingCharge);

            DB::beginTransaction();

            // ─── Step 1: Create NimbusPost Reverse Shipment ────────────────────
            $items = $order->orderItems->map(function ($item) {
                return [
                    'name'  => $item->product->title ?? $item->product->name ?? 'Product',
                    'qty'   => (string)$item->quantity,
                    'price' => (string)$item->price,
                    'sku'   => $item->product->sku ?? 'SKU-' . $item->product_id,
                ];
            })->toArray();

            $fullAddress = trim($order->address . ($order->landmark ? ', ' . $order->landmark : ''));

            // For REVERSE: consignee = customer (pickup), pickup = warehouse (destination)
            $reversePayload = [
                'order_number'        => 'RET-' . $order->order_number,
                'courier_id'          => $order->shipment?->courier_id
                                         ?? $request->input('courier_id')
                                         ?? (intval(config('services.nimbuspost.default_courier_id', 0)) ?: null),
                'package_weight'      => 500,
                'package_length'      => 17,
                'package_breadth'     => 10,
                'package_height'      => 5,
                'order_amount'        => (float)$order->total_amount,
                'request_auto_pickup' => 1,

                // REVERSE: customer address = pickup point
                'consignee' => [
                    'name'    => $order->customer_name,
                    'address' => $fullAddress,
                    'city'    => $order->city,
                    'state'   => $order->state,
                    'pincode' => (string)$order->pincode,
                    'phone'   => (string)preg_replace('/[^0-9]/', '', $order->phone),
                ],

                // REVERSE: warehouse = delivery point
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

                'order_items' => array_map(function ($item) {
                    return [
                        'name'  => $item['name'] ?: 'Product',
                        'qty'   => (int)$item['qty'],
                        'price' => (float)$item['price'],
                        'sku'   => $item['sku'] ?? 'SKU',
                    ];
                }, $items),
            ];

            $nimbusResponse = $nimbus->createReverseShipment($reversePayload);

            $returnAwb = null;
            $returnNimbusShipmentId = null;

            if (isset($nimbusResponse['status']) && $nimbusResponse['status'] === true) {
                $returnAwb = $nimbusResponse['data']['awb_number'] ?? null;
                $returnNimbusShipmentId = $nimbusResponse['data']['shipment_id'] ?? null;
                Log::info("Return reverse shipment created for order #{$order->order_number}. AWB: {$returnAwb}");
            } else {
                // Log the failure but continue — admin can handle manually
                Log::warning("NimbusPost reverse shipment failed for order #{$order->order_number}", [
                    'response' => $nimbusResponse
                ]);
            }

            // ─── Step 2: Trigger Razorpay Refund ───────────────────────────────
            $razorpayRefundId = null;
            $refundError = null;

            if ($order->payment_status === 'paid' && !empty($order->razorpay_payment_id)) {
                try {
                    $api = new \Razorpay\Api\Api(
                        config('services.razorpay.key'),
                        config('services.razorpay.secret')
                    );

                    $refundAmountPaise = (int)round($netRefund * 100);

                    $refund = $api->payment->fetch($order->razorpay_payment_id)->refund([
                        'amount' => $refundAmountPaise,
                        'notes'  => [
                            'order_number'    => $order->order_number,
                            'reason'          => 'Return: ' . ($order->return_reason ?? 'Customer return'),
                            'return_shipping' => "₹{$returnShippingCharge} deducted",
                        ],
                    ]);

                    $razorpayRefundId = $refund->id;
                    Log::info("Razorpay refund ₹{$netRefund} initiated for order #{$order->order_number}. Refund ID: {$razorpayRefundId}");

                } catch (\Exception $e) {
                    $refundError = $e->getMessage();
                    Log::error("Razorpay refund failed for order #{$order->order_number}: " . $e->getMessage());
                }
            }

            // ─── Step 3: Update Order ───────────────────────────────────────────
            $order->update([
                'return_status'             => 'approved',
                'return_approved_at'        => now(),
                'return_shipping_charge'    => $returnShippingCharge,
                'return_admin_note'         => $request->input('admin_note', 'Return approved by admin'),
                'return_awb'                => $returnAwb,
                'return_nimbus_shipment_id' => $returnNimbusShipmentId,
                'refund_status'             => $razorpayRefundId ? 'processing' : ($refundError ? 'failed' : 'pending'),
                'refund_amount'             => $netRefund,
                'razorpay_refund_id'        => $razorpayRefundId,
                'refund_requested_at'       => now(),
            ]);

            $order->logStatus("Return approved. Net refund: ₹{$netRefund}. Return AWB: " . ($returnAwb ?? 'Pending'));

            DB::commit();

            return response()->json([
                'success'        => true,
                'message'        => 'Return approved! ' . ($returnAwb ? "Return AWB: {$returnAwb}." : 'NimbusPost pickup pending.') . " Refund of ₹{$netRefund} " . ($razorpayRefundId ? 'initiated.' : 'pending — process manually.'),
                'return_awb'     => $returnAwb,
                'net_refund'     => $netRefund,
                'refund_error'   => $refundError,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("approveReturn error for order {$id}: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve return: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject a return request with admin note.
     */
    public function rejectReturn(Request $request, $id)
    {
        try {
            $order = Order::findOrFail($id);

            if ($order->return_status !== 'requested') {
                return response()->json([
                    'success' => false,
                    'message' => 'This order does not have a pending return request.'
                ], 422);
            }

            $request->validate(['admin_note' => 'required|string|max:500']);

            $order->update([
                'return_status'     => 'rejected',
                'return_admin_note' => $request->admin_note,
            ]);

            $order->logStatus('Return request rejected: ' . $request->admin_note);

            return response()->json([
                'success' => true,
                'message' => 'Return request rejected successfully.'
            ]);

        } catch (\Exception $e) {
            Log::error("rejectReturn error for order {$id}: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to reject return: ' . $e->getMessage()
            ], 500);
        }
    }
}
