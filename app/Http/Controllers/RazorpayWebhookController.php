<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;
use App\Mail\PaymentRefundInitiated;

class RazorpayWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->header('x-razorpay-signature');
        $webhookSecret = config('services.razorpay.webhook_secret');
        $webhookId = $request->header('x-razorpay-event-id');

        if (!$signature || !$webhookSecret || !$webhookId) {
            Log::warning('[SECURITY] Razorpay Webhook: Missing signature, secret, or event ID.', [
                'ip' => $request->ip()
            ]);
            return response()->json(['error' => 'Invalid request'], 400);
        }

        $api = new Api(config('services.razorpay.key_id'), config('services.razorpay.key_secret'));

        try {
            $api->utility->verifyWebhookSignature($payload, $signature, $webhookSecret);
        } catch (SignatureVerificationError $e) {
            Log::alert('[SECURITY] Razorpay Webhook: Signature verification failed.', [
                'error' => $e->getMessage(),
                'ip' => $request->ip(),
                'payload' => $payload
            ]);
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        $data = json_decode($payload, true);
        $event = $data['event'] ?? '';

        // Only process supported events
        $supportedEvents = [
            'order.paid', 
            'payment.captured', 
            'payment.failed',
            'refund.created',
            'refund.processed',
            'refund.failed'
        ];
        if (!in_array($event, $supportedEvents)) {
            return response()->json(['status' => 'ignored']);
        }

        // 1. Atomic Replay Attack Protection: Insert Or Ignore
        $inserted = DB::table('razorpay_webhooks')->insertOrIgnore([
            'webhook_id' => $webhookId,
            'event' => $event,
            'payload' => $payload,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if (!$inserted) {
            Log::info("Razorpay Webhook: Duplicate event ignored. ID: {$webhookId}");
            return response()->json(['message' => 'Webhook already processed']);
        }

        Log::info("Razorpay Webhook Received: {$event} (ID: {$webhookId})");

        if ($event === 'order.paid' || $event === 'payment.captured') {
            $payment = $data['payload']['payment']['entity'] ?? null;
            $razorpayOrderId = $payment['order_id'] ?? null;

            if (!$payment || !$razorpayOrderId) {
                Log::alert('[SECURITY] Razorpay Webhook: Malformed payment entity.', ['payload' => $data]);
                return response()->json(['error' => 'Malformed payload'], 400);
            }

            try {
                DB::transaction(function () use ($razorpayOrderId, $payment, $webhookId) {
                    // 2. Row-level locking to prevent race conditions
                    $order = Order::where('razorpay_order_id', $razorpayOrderId)->lockForUpdate()->first();

                    if (!$order) {
                        Log::alert("[SECURITY] Razorpay Webhook: Order not found for razorpay_order_id: {$razorpayOrderId}");
                        abort(404, 'Order not found');
                    }

                    // Map order_number to webhook log for traceability
                    DB::table('razorpay_webhooks')
                        ->where('webhook_id', $webhookId)
                        ->update(['order_number' => $order->order_number]);

                    // 3. Idempotency Check: Ensure no unpaid -> paid -> paid again transition
                    if ($order->payment_status === 'paid') {
                        Log::info("Razorpay Webhook: Order {$order->order_number} is already paid. Ignoring.");
                        return;
                    }

                    // 3.5. Handle Cancelled or Failed Orders (Autorefund immediately)
                    if (in_array($order->status, ['cancelled', 'failed'])) {
                        Log::info("Razorpay Webhook: Order {$order->order_number} is already {$order->status}. Refunding.");

                        if (empty($order->razorpay_refund_id)) {
                            $order->update([
                                'payment_status' => 'paid',
                                'paid_at' => now(),
                                'razorpay_payment_id' => $payment['id'],
                                'refund_status' => 'pending',
                                'refund_requested_at' => now(),
                            ]);

                            try {
                                $refundApi = new Api(
                                    config('services.razorpay.key_id'),
                                    config('services.razorpay.key_secret')
                                );
                                $refund = $refundApi->payment->fetch($payment['id'])->refund([
                                    'amount' => $payment['amount'],
                                    'speed' => 'optimum',
                                    'notes' => [
                                        'reason' => 'Order cancelled/failed - auto refund',
                                        'order_number' => $order->order_number,
                                    ]
                                ]);

                                $order->update([
                                    'refund_status' => 'initiated',
                                    'razorpay_refund_id' => $refund['id'] ?? null,
                                    'refund_processed_at' => now(),
                                ]);

                                Log::info("Razorpay Webhook: Refund initiated for cancelled/failed Order {$order->order_number}. Refund ID: " . ($refund['id'] ?? 'N/A'));
                            } catch (\Exception $refundEx) {
                                Log::alert("CRITICAL: Razorpay Webhook Refund FAILED for cancelled/failed Order {$order->order_number}: " . $refundEx->getMessage());
                            }

                            if (method_exists($order, 'logStatus')) {
                                $order->logStatus("System refunded: Payment received for an order that was already cancelled or failed. Refund initiated.", null);
                            }
                        }
                        return;
                    }

                    // 4. Verify Amount Matches
                    $expectedAmount = (int) round($order->total_amount * 100);
                    $actualAmount = (int) $payment['amount'];
                    $amountMismatch = $actualAmount !== $expectedAmount;
                    $currencyMismatch = ($payment['currency'] ?? '') !== 'INR';

                    if ($amountMismatch || $currencyMismatch) {
                        Log::alert("[SECURITY] Razorpay Webhook: Payment validation mismatch for Order {$order->order_number}.", [
                            'expected_amount' => $expectedAmount,
                            'actual_amount' => $actualAmount,
                            'currency' => $payment['currency'] ?? 'UNKNOWN'
                        ]);

                        if (empty($order->razorpay_refund_id)) {
                            $order->update([
                                'status' => 'failed',
                                'payment_status' => 'paid',
                                'paid_at' => now(),
                                'razorpay_payment_id' => $payment['id'],
                                'refund_status' => 'pending',
                                'refund_requested_at' => now(),
                                'cancellation_reason' => 'Security: Payment validation mismatch (amount/currency)',
                            ]);

                            try {
                                $refundApi = new Api(
                                    config('services.razorpay.key_id'),
                                    config('services.razorpay.key_secret')
                                );
                                $refund = $refundApi->payment->fetch($payment['id'])->refund([
                                    'amount' => $payment['amount'],
                                    'speed' => 'optimum',
                                    'notes' => [
                                        'reason' => 'Security validation mismatch - auto refund',
                                        'order_number' => $order->order_number,
                                    ]
                                ]);

                                $order->update([
                                    'refund_status' => 'initiated',
                                    'razorpay_refund_id' => $refund['id'] ?? null,
                                    'refund_processed_at' => now(),
                                ]);

                                Log::info("Razorpay Webhook: Refund initiated for mismatch on Order {$order->order_number}. Refund ID: " . ($refund['id'] ?? 'N/A'));
                            } catch (\Exception $refundEx) {
                                Log::alert("CRITICAL: Razorpay Webhook Refund FAILED for mismatched Order {$order->order_number}: " . $refundEx->getMessage());
                            }

                            if (method_exists($order, 'logStatus')) {
                                $order->logStatus("Security Alert: Amount/Currency mismatch. Order marked failed. Refund initiated.", null);
                            }
                        }

                        abort(400, 'Security validation mismatch');
                    }

                    // 6. Verify Captured Status
                    if (($payment['status'] ?? '') !== 'captured') {
                        Log::alert("[SECURITY] Razorpay Webhook: Payment not captured for order {$order->order_number}. Status: " . ($payment['status'] ?? 'UNKNOWN'));
                        abort(400, 'Payment not captured');
                    }

                    // 7. Pre-Flight Inventory Lock
                    $outOfStock = false;
                    foreach ($order->orderItems as $item) {
                        $product = \App\Models\Product::lockForUpdate()->find($item->product_id);
                        if (!$product || $product->stock < $item->quantity) {
                            $outOfStock = true;
                            break;
                        }
                    }

                    if ($outOfStock) {
                        if (empty($order->razorpay_refund_id)) {
                            // Mark as cancelled with paid status
                            $order->update([
                                'payment_status' => 'paid',
                                'paid_at' => now(),
                                'razorpay_payment_id' => $payment['id'],
                                'status' => 'cancelled',
                                'cancellation_reason' => 'System Auto-Cancel: Out of stock during fulfillment',
                                'refund_status' => 'pending',
                                'refund_requested_at' => now(),
                            ]);

                            // Trigger actual Razorpay Refund via API
                            try {
                                $refundApi = new Api(
                                    config('services.razorpay.key_id'),
                                    config('services.razorpay.key_secret')
                                );
                                $refund = $refundApi->payment->fetch($payment['id'])->refund([
                                    'amount' => $payment['amount'], // Full refund in paise
                                    'speed' => 'optimum',
                                    'notes' => [
                                        'reason' => 'Out of stock - auto refund',
                                        'order_number' => $order->order_number,
                                    ]
                                ]);

                                $order->update([
                                    'refund_status' => 'initiated',
                                    'razorpay_refund_id' => $refund['id'] ?? null,
                                    'refund_processed_at' => now(),
                                ]);

                                Log::info("Razorpay Refund Initiated for Order {$order->order_number}. Refund ID: " . ($refund['id'] ?? 'N/A'));
                            } catch (\Exception $refundEx) {
                                Log::alert("CRITICAL: Razorpay Refund FAILED for Order {$order->order_number}: " . $refundEx->getMessage());
                            }

                            // Log the timeline
                            if (method_exists($order, 'logStatus')) {
                                $order->logStatus("System cancelled: Item went out of stock during payment. Refund initiated via Razorpay.", null);
                            }

                            Log::alert("CRITICAL: Order {$order->order_number} cancelled due to stock race condition. Refund initiated.");
                        }
                        return;
                    }

                    // 8. Process valid payment (sufficient stock)
                    $order->update([
                        'payment_status' => 'paid',
                        'paid_at' => now(),
                        'razorpay_payment_id' => $payment['id'],
                        'status' => 'processing'
                    ]);

                    // Send Payment Successful / Bill Email
                    try {
                        \Illuminate\Support\Facades\Mail::to($order->email)
                            ->queue(new \App\Mail\PaymentSuccessful($order));
                    } catch (\Exception $mailEx) {
                        Log::error("Failed to send payment successful email for Order {$order->order_number}: " . $mailEx->getMessage());
                    }

                    Log::info("Razorpay Webhook: Order {$order->order_number} successfully marked as paid and processing.");
                });
            } catch (\Exception $e) {
                Log::error("Razorpay Webhook Processing Failed: " . $e->getMessage());
                return response()->json(['error' => 'Processing failed'], 500);
            }
        } elseif ($event === 'payment.failed') {
            $payment = $data['payload']['payment']['entity'] ?? null;
            $razorpayOrderId = $payment['order_id'] ?? null;
            
            Log::error("Razorpay Webhook: Payment failed for order {$razorpayOrderId}. Reason: " . ($payment['error_description'] ?? 'Unknown'));
            
            if ($razorpayOrderId) {
                DB::table('orders')->where('razorpay_order_id', $razorpayOrderId)
                    ->where('payment_status', '!=', 'paid')
                    ->update(['status' => 'failed']);
            }
        } elseif (in_array($event, ['refund.processed', 'refund.created', 'refund.failed'])) {
            $refund = $data['payload']['refund']['entity'] ?? null;
            
            if (!$refund) {
                Log::alert('Razorpay Webhook: Malformed refund entity.', ['payload' => $data]);
                return response()->json(['error' => 'Malformed payload'], 400);
            }

            $paymentId = $refund['payment_id'] ?? null;
            $refundId = $refund['id'] ?? null;

            try {
                DB::transaction(function () use ($paymentId, $refundId, $refund, $event, $webhookId) {
                    // Find the order by matching refund ID first, then payment transaction ID, then notes
                    $order = Order::where('razorpay_refund_id', $refundId)
                        ->orWhere('payment_transaction_id', $paymentId)
                        ->orWhere('razorpay_payment_id', $paymentId)
                        ->lockForUpdate()
                        ->first();

                    if (!$order && isset($refund['notes']['order_number'])) {
                        $order = Order::where('order_number', $refund['notes']['order_number'])
                            ->lockForUpdate()
                            ->first();
                    }

                    if (!$order) {
                        Log::warning("Razorpay Webhook: Order not found for refund. Refund ID: {$refundId}, Payment ID: {$paymentId}");
                        return;
                    }

                    // Map order_number to webhook log for traceability
                    DB::table('razorpay_webhooks')
                        ->where('webhook_id', $webhookId)
                        ->update(['order_number' => $order->order_number]);

                    // Unidirectional State Guard: Don't allow downgrade from completed/processed state
                    if ($order->refund_status === 'completed' && $event !== 'refund.processed') {
                        Log::info("Razorpay Webhook: Order {$order->order_number} refund is already completed. Ignoring status transition to: {$event}");
                        return;
                    }

                    $statusMap = [
                        'refund.processed' => 'completed',
                        'refund.created' => 'initiated',
                        'refund.failed' => 'failed',
                    ];

                    $newRefundStatus = $statusMap[$event] ?? 'initiated';

                    $updateData = [
                        'refund_status' => $newRefundStatus,
                        'razorpay_refund_id' => $refundId,
                    ];

                    if ($event === 'refund.processed') {
                        $updateData['refund_amount'] = $refund['amount'] / 100; // paise to INR
                        $updateData['refund_processed_at'] = now();
                        if (isset($refund['acquirer_data']['arn'])) {
                            $updateData['refund_arn'] = $refund['acquirer_data']['arn'];
                        }
                    }

                    $order->update($updateData);

                    if (method_exists($order, 'logStatus')) {
                        $order->logStatus("Refund update from Razorpay Webhook: status is now {$newRefundStatus}. Refund ID: {$refundId}", null);
                    }
                });
            } catch (\Exception $e) {
                Log::error("Razorpay Webhook Refund Processing Failed for Refund ID {$refundId}: " . $e->getMessage());
                return response()->json(['error' => 'Processing failed'], 500);
            }
        }

        return response()->json(['status' => 'success']);
    }
}
