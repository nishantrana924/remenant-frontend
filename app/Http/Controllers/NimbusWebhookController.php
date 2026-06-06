<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Services\ShipmentStatusValidator;
use App\Exceptions\NimbusWebhookException;

class NimbusWebhookController extends Controller
{
    protected $validator;

    public function __construct(ShipmentStatusValidator $validator)
    {
        $this->validator = $validator;
    }

    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->header('x-nimbus-signature');
        $timestamp = $request->header('x-nimbus-timestamp');
        $webhookId = hash('sha256', $timestamp . $payload); // Deterministic replay key
        $secret = config('services.nimbuspost.webhook_secret');

        try {
            // 1. Validate Payload Size (Reject if > 64KB)
            if (strlen($payload) > 65536) {
                throw new NimbusWebhookException('Payload exceeds maximum allowed size.', 413, ['size' => strlen($payload)]);
            }

            // 2. Validate Headers & Secret
            if (!$signature || !$timestamp || !$secret) {
                throw new NimbusWebhookException('Missing signature, timestamp, or secret.', 401, ['ip' => $request->ip()]);
            }

            // 3. Validate Timestamp (Reject if older than 5 minutes)
            $tolerance = 300;
            if (abs(time() - (int)$timestamp) > $tolerance) {
                throw new NimbusWebhookException('Timestamp expired.', 401, ['timestamp' => $timestamp, 'ip' => $request->ip()]);
            }

            // 4. Verify HMAC SHA256 Signature
            $expectedSignature = hash_hmac('sha256', $timestamp . '.' . $payload, $secret);
            if (!hash_equals($expectedSignature, $signature)) {
                throw new NimbusWebhookException('Signature mismatch.', 401, ['payload' => $payload, 'ip' => $request->ip()]);
            }

            // 5. JSON Parsing and Validation
            $data = json_decode($payload, true);
            if (!$data) {
                throw new NimbusWebhookException('Malformed JSON payload.', 400, ['payload' => $payload]);
            }

            $awb = $data['awb_number'] ?? $data['waybill'] ?? null;
            $status = $data['current_status'] ?? $data['status'] ?? null;

            if (!$awb || !$status) {
                throw new NimbusWebhookException('Missing required fields: awb_number or status.', 422, ['payload' => $data]);
            }

            // 6. Validate AWB Format (Basic alphanumeric check)
            if (!preg_match('/^[A-Z0-9]+$/i', $awb)) {
                throw new NimbusWebhookException('Invalid AWB format.', 422, ['awb' => $awb]);
            }

            // 7. Replay Attack Protection
            $inserted = DB::table('webhook_logs')->insertOrIgnore([
                'webhook_id' => $webhookId,
                'provider'   => 'nimbuspost',
                'event_type' => $status,
                'payload'    => $payload,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if (!$inserted) {
                $this->audit($webhookId, $request, 'ignored', 'Webhook already processed');
                return response()->json(['message' => 'Webhook already processed'], 200);
            }

            // 8. Map and validate status
            $mappedStatus = $this->validator->mapNimbusStatus($status);
            if (!$mappedStatus) {
                $this->audit($webhookId, $request, 'ignored', "Unsupported status: {$status}");
                return response()->json(['status' => 'ignored'], 200);
            }

            // 9. Process using DB Transaction & Row Locking
            DB::transaction(function () use ($awb, $status, $mappedStatus, $webhookId, $request) {
                $order = Order::where('tracking_id', $awb)->lockForUpdate()->first();

                if (!$order) {
                    throw new NimbusWebhookException("No order found for AWB {$awb}", 404, ['awb' => $awb]);
                }

                // Validate Status Transitions using Matrix
                $currentStatus = $order->status;
                if (!$this->validator->isValidTransition($currentStatus, $mappedStatus)) {
                    // Safe ignoring of redundant but harmless transitions
                    if ($currentStatus === $mappedStatus) {
                        return; 
                    }
                    throw new NimbusWebhookException("Invalid status transition", 422, [
                        'order_id' => $order->id,
                        'from' => $currentStatus,
                        'to' => $mappedStatus
                    ]);
                }

                $order->update([
                    'delivery_status' => $status,
                    'status'          => $mappedStatus,
                ]);

                $this->audit($webhookId, $request, 'success', "Updated order {$order->id} to {$mappedStatus}");
            });

            return response()->json(['status' => 'success'], 200);

        } catch (NimbusWebhookException $e) {
            Log::channel('nimbus_security')->error("Nimbus Webhook Error: " . $e->getMessage(), $e->context);
            $this->audit($webhookId, $request, 'failed', $e->getMessage());
            return response()->json(['error' => $e->getMessage()], $e->getCode() >= 400 ? $e->getCode() : 400);
        } catch (\Exception $e) {
            Log::channel('nimbus_security')->critical("Nimbus Webhook Fatal: " . $e->getMessage());
            $this->audit($webhookId, $request, 'failed', 'Internal server error');
            return response()->json(['error' => 'Processing failed'], 500);
        }
    }

    private function audit(string $webhookId, Request $request, string $status, string $result): void
    {
        DB::table('webhook_audits')->insert([
            'webhook_id' => $webhookId,
            'provider'   => 'nimbuspost',
            'ip'         => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
            'status'     => $status,
            'result'     => $result,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
