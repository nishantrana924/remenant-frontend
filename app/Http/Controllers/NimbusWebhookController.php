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
        $payload   = $request->getContent();
        $webhookId = hash('sha256', $payload); // Deterministic replay key based on payload content
        $secret    = config('services.nimbuspost.webhook_secret');

        // Log all incoming requests for diagnostics
        Log::channel('nimbus_security')->info('NimbusPost Webhook Received', [
            'ip'      => $request->ip(),
            'headers' => $request->headers->all(),
            'payload' => substr($payload, 0, 500),
        ]);

        try {
            // 1. Validate Payload Size (Reject if > 64KB)
            if (strlen($payload) > 65536) {
                throw new NimbusWebhookException('Payload exceeds maximum allowed size.', 413, ['size' => strlen($payload)]);
            }

            // 2. Verify HMAC Signature (per official NimbusPost webhook docs)
            // Header: X-Hmac-SHA256
            // Format: base64_encode(hash_hmac('sha256', $payload, $secret, true))
            $receivedSignature = $request->header('X-Hmac-SHA256');

            if ($secret && $receivedSignature) {
                $expectedSignature = base64_encode(hash_hmac('sha256', $payload, $secret, true));
                if (!hash_equals($expectedSignature, $receivedSignature)) {
                    throw new NimbusWebhookException('Signature mismatch.', 401, ['ip' => $request->ip()]);
                }
            } elseif ($secret && !$receivedSignature) {
                // Secret is configured but NimbusPost didn't send the header — log and allow
                // (happens when webhook secret is not set in NimbusPost panel)
                Log::channel('nimbus_security')->warning('X-Hmac-SHA256 header missing. Ensure secret is set in NimbusPost panel.', [
                    'ip' => $request->ip(),
                ]);
            }

            // 3. JSON Parsing and Validation
            $data = json_decode($payload, true);
            if (!$data) {
                throw new NimbusWebhookException('Malformed JSON payload.', 400, ['payload' => $payload]);
            }

            // NimbusPost payload fields: awb_number, status, event_time, location, message, rto_awb
            $awb    = $data['awb_number'] ?? null;
            $status = $data['status'] ?? null;

            if (!$awb || !$status) {
                throw new NimbusWebhookException('Missing required fields: awb_number or status.', 422, ['payload' => $data]);
            }

            // 4. Validate AWB Format
            if (!preg_match('/^[A-Z0-9\-]+$/i', $awb)) {
                throw new NimbusWebhookException('Invalid AWB format.', 422, ['awb' => $awb]);
            }

            // 5. Replay Attack Protection
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

            // 6. Map NimbusPost status to internal status
            // mapNimbusStatus() handles case-insensitive matching
            $mappedStatus = $this->validator->mapNimbusStatus($status);
            if (!$mappedStatus) {
                $this->audit($webhookId, $request, 'ignored', "Unsupported status: {$status}");
                Log::channel('nimbus_security')->info("Unmapped NimbusPost status ignored: {$status}", ['awb' => $awb]);
                return response()->json(['status' => 'ignored', 'reason' => "Unrecognized status: {$status}"], 200);
            }

            // 7. Process using DB Transaction & Row Locking
            DB::transaction(function () use ($awb, $mappedStatus, $webhookId, $request, $data) {
                $order = Order::where('tracking_id', $awb)->lockForUpdate()->first();

                if (!$order) {
                    Log::channel('nimbus_security')->warning("No order found for AWB: {$awb}", ['awb' => $awb]);
                    return; // Graceful — don't cause NimbusPost to retry for unknown AWBs
                }

                // Validate Status Transitions
                $currentStatus = $order->status;
                if (!$this->validator->isValidTransition($currentStatus, $mappedStatus)) {
                    if ($currentStatus === $mappedStatus) {
                        return; // Idempotent — same status, skip
                    }
                    Log::channel('nimbus_security')->warning("Invalid status transition ignored", [
                        'order_id' => $order->id,
                        'from'     => $currentStatus,
                        'to'       => $mappedStatus,
                    ]);
                    return; // Log and skip — do not crash
                }

                // ✅ FIX: Use $mappedStatus ('shipped') not raw Nimbus string ('in transit')
                // Raw strings violate the DB ENUM constraint on delivery_status column
                $order->update([
                    'delivery_status' => $mappedStatus,
                    'status'          => $mappedStatus,
                    'last_location'   => $data['location'] ?? null,
                ]);

                // Log to order timeline if method exists
                if (method_exists($order, 'logStatus')) {
                    $order->logStatus("Auto-updated via NimbusPost webhook to: {$mappedStatus}");
                }

                $this->audit($webhookId, $request, 'success', "Updated order {$order->id} to {$mappedStatus}");
                Log::channel('nimbus_security')->info("Order #{$order->order_number} updated to {$mappedStatus} via webhook.");
            });

            return response()->json(['status' => 'success'], 200);

        } catch (NimbusWebhookException $e) {
            Log::channel('nimbus_security')->error("Nimbus Webhook Error: " . $e->getMessage(), $e->context);
            $this->audit($webhookId, $request, 'failed', $e->getMessage());
            return response()->json(['error' => $e->getMessage()], $e->getCode() >= 400 ? $e->getCode() : 400);
        } catch (\Exception $e) {
            Log::channel('nimbus_security')->critical("Nimbus Webhook Fatal: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
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
