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
        $supportedEvents = ['order.paid', 'payment.captured', 'payment.failed'];
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

                    // 4. Verify Amount Matches
                    $expectedAmount = (int) round($order->total_amount * 100);
                    $actualAmount = (int) $payment['amount'];

                    if ($actualAmount !== $expectedAmount) {
                        Log::alert("[SECURITY] Razorpay Webhook: Amount mismatch for order {$order->order_number}.", [
                            'expected' => $expectedAmount,
                            'actual' => $actualAmount
                        ]);
                        abort(400, 'Amount mismatch');
                    }

                    // 5. Verify Currency Matches
                    if (($payment['currency'] ?? '') !== 'INR') {
                        Log::alert("[SECURITY] Razorpay Webhook: Currency mismatch for order {$order->order_number}.", [
                            'currency' => $payment['currency'] ?? 'UNKNOWN'
                        ]);
                        abort(400, 'Currency mismatch');
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
                        // Mark as cancelled with paid status
                        $order->update([
                            'payment_status' => 'paid',
                            'razorpay_payment_id' => $payment['id'],
                            'status' => 'cancelled',
                            'cancellation_reason' => 'System Auto-Cancel: Out of stock during fulfillment',
                            'refund_status' => 'pending'
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
                            ]);

                            Log::info("Razorpay Refund Initiated for Order {$order->order_number}. Refund ID: " . ($refund['id'] ?? 'N/A'));
                        } catch (\Exception $refundEx) {
                            Log::alert("CRITICAL: Razorpay Refund FAILED for Order {$order->order_number}: " . $refundEx->getMessage());
                            // Keep refund_status as 'pending' so admin can manually process
                        }

                        // Send refund notification email to customer
                        try {
                            \Illuminate\Support\Facades\Mail::to($order->email)
                                ->queue(new PaymentRefundInitiated($order));
                        } catch (\Exception $mailEx) {
                            Log::error("Failed to send refund email for Order {$order->order_number}: " . $mailEx->getMessage());
                        }

                        // Log the timeline
                        if (method_exists($order, 'logStatus')) {
                            $order->logStatus("System cancelled: Item went out of stock during payment. Refund initiated via Razorpay.", null);
                        }

                        Log::alert("CRITICAL: Order {$order->order_number} cancelled due to stock race condition. Refund initiated.");
                        return;
                    }

                    // 8. Process valid payment (sufficient stock)
                    $order->update([
                        'payment_status' => 'paid',
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
        }

        return response()->json(['status' => 'success']);
    }
}
