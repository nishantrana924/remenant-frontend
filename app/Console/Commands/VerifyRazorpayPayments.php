<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use Razorpay\Api\Api;
use App\Jobs\ProcessOrderRefundJob;

class VerifyRazorpayPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'razorpay:verify-payments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verify unpaid Razorpay orders from the last 72 hours against the Razorpay API and process refunds or completions.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $razorpayKey = config('services.razorpay.key_id');
        $razorpaySecret = config('services.razorpay.key_secret');

        if (!$razorpayKey || !$razorpaySecret) {
            $this->error('Razorpay credentials not configured.');
            return;
        }

        $api = new Api($razorpayKey, $razorpaySecret);

        // Fetch unpaid orders created between 10 minutes ago and 72 hours ago
        $orders = Order::where('payment_method', 'razorpay')
            ->where('payment_status', 'unpaid')
            ->whereNotNull('razorpay_order_id')
            ->where('created_at', '<=', now()->subMinutes(10))
            ->where('created_at', '>=', now()->subHours(72))
            ->get();

        if ($orders->isEmpty()) {
            $this->info('No pending unpaid Razorpay orders to check.');
            return;
        }

        $this->info("Checking {$orders->count()} orders...");
        Log::info("VerifyRazorpayPayments: Checking {$orders->count()} unpaid orders from the last 72 hours.");

        foreach ($orders as $order) {
            try {
                $razorpayOrder = $api->order->fetch($order->razorpay_order_id);
                $payments = $razorpayOrder->payments();

                $capturedPayment = null;
                if ($payments) {
                    foreach ($payments as $payment) {
                        if ($payment->status === 'captured') {
                            $capturedPayment = $payment;
                            break;
                        }
                    }
                }

                if ($capturedPayment) {
                    $this->info("Order #{$order->order_number} has captured payment: {$capturedPayment->id}");
                    Log::info("VerifyRazorpayPayments: Found captured payment {$capturedPayment->id} for Order #{$order->order_number}");

                    // Perform security checks (amount & currency)
                    $expectedAmount = (int) round($order->total_amount * 100);
                    $actualAmount = (int) $capturedPayment->amount;
                    $amountMismatch = $actualAmount !== $expectedAmount;
                    $currencyMismatch = ($capturedPayment->currency ?? '') !== 'INR';

                    if ($amountMismatch || $currencyMismatch) {
                        Log::alert("VerifyRazorpayPayments: Security mismatch for Order #{$order->order_number}. Refunding.", [
                            'expected' => $expectedAmount,
                            'actual' => $actualAmount,
                            'currency' => $capturedPayment->currency ?? 'UNKNOWN'
                        ]);
                        
                        if (empty($order->razorpay_refund_id)) {
                            $order->update([
                                'payment_status' => 'paid',
                                'paid_at' => now(),
                                'razorpay_payment_id' => $capturedPayment->id,
                                'status' => 'failed',
                                'refund_status' => 'pending',
                                'refund_requested_at' => now(),
                                'payment_reconciled_at' => now(),
                                'cancellation_reason' => 'Security Mismatch (Amount/Currency) in Cron Verification'
                            ]);

                            ProcessOrderRefundJob::dispatch($order->id);
                        }
                        continue;
                    }

                    // Check if order is cancelled or failed
                    if (in_array($order->status, ['cancelled', 'failed'])) {
                        Log::info("VerifyRazorpayPayments: Order #{$order->order_number} is already cancelled/failed. Refunding.");
                        
                        if (empty($order->razorpay_refund_id)) {
                            $order->update([
                                'payment_status' => 'paid',
                                'paid_at' => now(),
                                'razorpay_payment_id' => $capturedPayment->id,
                                'refund_status' => 'pending',
                                'refund_requested_at' => now(),
                                'payment_reconciled_at' => now(),
                            ]);

                            ProcessOrderRefundJob::dispatch($order->id);
                        }
                        continue;
                    }

                    // Pre-flight stock check
                    $outOfStock = false;
                    foreach ($order->orderItems as $item) {
                        $product = \App\Models\Product::find($item->product_id);
                        if (!$product || $product->stock < $item->quantity) {
                            $outOfStock = true;
                            break;
                        }
                    }

                    if ($outOfStock) {
                        Log::warn("VerifyRazorpayPayments: Order #{$order->order_number} out of stock. Refunding.");
                        
                        if (empty($order->razorpay_refund_id)) {
                            $order->update([
                                'payment_status' => 'paid',
                                'paid_at' => now(),
                                'razorpay_payment_id' => $capturedPayment->id,
                                'status' => 'cancelled',
                                'cancellation_reason' => 'Out of stock during cron fulfillment verification',
                                'refund_status' => 'pending',
                                'refund_requested_at' => now(),
                                'payment_reconciled_at' => now(),
                            ]);

                            ProcessOrderRefundJob::dispatch($order->id);
                        }
                        continue;
                    }

                    // Otherwise, complete order payment!
                    $order->update([
                        'payment_status' => 'paid',
                        'paid_at' => now(),
                        'razorpay_payment_id' => $capturedPayment->id,
                        'status' => 'processing',
                        'payment_reconciled_at' => now(),
                    ]);

                    Log::info("VerifyRazorpayPayments: Successfully reconciled and completed Order #{$order->order_number}");

                } else {
                    $this->line("Order #{$order->order_number} has no captured payments on Razorpay.");
                }

            } catch (\Exception $e) {
                Log::error("VerifyRazorpayPayments: Failed checking Order #{$order->order_number}: " . $e->getMessage());
            }
        }

        // 2. Reconcile Active/Pending Refunds
        $refundOrders = Order::whereIn('refund_status', ['pending', 'initiated', 'processing'])
            ->whereNotNull('razorpay_refund_id')
            ->get();

        if ($refundOrders->isNotEmpty()) {
            $this->info("Checking {$refundOrders->count()} pending refunds...");
            Log::info("VerifyRazorpayPayments: Checking {$refundOrders->count()} active refunds for reconciliation.");

            foreach ($refundOrders as $order) {
                try {
                    $refund = $api->refund->fetch($order->razorpay_refund_id);
                    if ($refund) {
                        \DB::transaction(function () use ($order, $refund) {
                            $order = Order::where('id', $order->id)->lockForUpdate()->first();

                            // Unidirectional status transition guard
                            if ($order->refund_status === 'completed' && $refund->status !== 'processed') {
                                return;
                            }

                            $statusMap = [
                                'processed' => 'completed',
                                'pending' => 'initiated',
                                'failed' => 'failed',
                            ];

                            $newRefundStatus = $statusMap[$refund->status] ?? 'initiated';

                            $updateData = [
                                'refund_status' => $newRefundStatus,
                            ];

                            if ($refund->status === 'processed') {
                                $updateData['refund_amount'] = $refund->amount / 100;
                                $updateData['refund_processed_at'] = now();
                                if (isset($refund->acquirer_data) && isset($refund->acquirer_data->arn)) {
                                    $updateData['refund_arn'] = $refund->acquirer_data->arn;
                                }
                            }

                            $order->update($updateData);

                            if (method_exists($order, 'logStatus')) {
                                $order->logStatus("Refund reconciled via Cron: status is now {$newRefundStatus}. Refund ID: {$refund->id}", 'system');
                            }
                            
                            $this->info("Reconciled Refund #{$order->razorpay_refund_id} to status: {$newRefundStatus}");
                        });
                    }
                } catch (\Exception $e) {
                    Log::error("VerifyRazorpayPayments: Failed reconciling refund for Order #{$order->order_number}: " . $e->getMessage());
                }
            }
        }

        // 3. Auto-Recover Stuck Refunds (pending status but no refund ID for > 20 mins)
        $stuckRefundOrders = Order::where('refund_status', 'pending')
            ->whereNull('razorpay_refund_id')
            ->where('payment_status', 'paid')
            ->where('updated_at', '<=', now()->subMinutes(20))
            ->get();

        foreach ($stuckRefundOrders as $stuckOrder) {
            $this->info("Stuck refund detected for Order #{$stuckOrder->order_number}. Re-dispatching ProcessOrderRefundJob.");
            Log::warning("VerifyRazorpayPayments: Stuck refund detected for Order #{$stuckOrder->order_number}. Re-dispatching ProcessOrderRefundJob.");
            ProcessOrderRefundJob::dispatch($stuckOrder->id);
        }
    }
}
