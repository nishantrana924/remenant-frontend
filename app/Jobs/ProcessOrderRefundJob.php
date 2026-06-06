<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\OrderTimeline;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Razorpay\Api\Api;

class ProcessOrderRefundJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $orderId;

    /**
     * Create a new job instance.
     */
    public function __construct($orderId)
    {
        $this->orderId = $orderId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            \Illuminate\Support\Facades\DB::transaction(function () {
                $order = Order::where('id', $this->orderId)->lockForUpdate()->first();

                if (!$order || in_array($order->refund_status, ['processing', 'completed']) || $order->payment_status !== 'paid') {
                    return; // Ensure idempotency
                }

                $razorpayKey = config('services.razorpay.key_id');
                $razorpaySecret = config('services.razorpay.key_secret');

                if (!$razorpayKey || !$razorpaySecret) {
                    throw new \Exception("Razorpay credentials missing.");
                }

                $api = new Api($razorpayKey, $razorpaySecret);

                $paymentId = $order->razorpay_payment_id ?? $order->payment_transaction_id;

                if (!$paymentId) {
                    throw new \Exception("No payment transaction ID found for Order #{$order->order_number}");
                }

                // Execute Refund API call
                $refund = $api->payment->fetch($paymentId)->refund([
                    'amount' => $order->total_amount * 100 // Amount in paise
                ]);
                
                $order->update([
                    'refund_status' => 'processing',
                    'refund_amount' => $order->total_amount,
                    'razorpay_refund_id' => $refund->id,
                ]);

                $order->logStatus("Refund initiated. Refund ID: {$refund->id}", 'system');

                // If Razorpay returns status as processed immediately (sometimes it does, sometimes it's pending)
                // For safety, we mark as completed if it doesn't explicitly throw an error, 
                // but we should store the ARN if provided.
                if (isset($refund->arn) || (isset($refund->status) && $refund->status === 'processed')) {
                    $order->update([
                        'refund_status' => 'completed',
                        'refund_arn' => $refund->arn ?? null,
                    ]);
                    $order->logStatus("Refund completed. Refund ID: {$refund->id}", 'system');
                }

                Log::info("Refund processed for Order #{$order->order_number}. Refund ID: {$refund->id}");
            });
        } catch (\Exception $e) {
            $order = Order::find($this->orderId);
            if ($order) {
                Log::error("Refund failed for Order #{$order->order_number}: " . $e->getMessage());
                
                $order->update([
                    'refund_status' => 'failed'
                ]);

                $order->logStatus("Refund processing failed. Reason: " . $e->getMessage(), 'system');
            }
        }
    }
}
