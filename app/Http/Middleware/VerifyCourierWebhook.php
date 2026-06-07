<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cache;
use App\Services\Warehouse\WarehouseAuditService;
use Illuminate\Support\Facades\Log;

class VerifyCourierWebhook
{
    protected WarehouseAuditService $auditService;

    public function __construct(WarehouseAuditService $auditService)
    {
        $this->auditService = $auditService;
    }

    public function handle(Request $request, Closure $next): Response
    {
        // 1. IP Whitelist Validation
        $bypassWhitelist = config('warehouse.bypass_webhook_ip_whitelist', false);
        $allowedIps = config('warehouse.courier_ips', ['127.0.0.1']);
        
        if (!$bypassWhitelist && !in_array($request->ip(), $allowedIps)) {
            $this->auditService->log(null, 'webhook_ip_rejected', null, "Rejected IP: {$request->ip()}");
            return response()->json(['error' => 'Forbidden IP'], 403);
        }

        // 2. Timestamp Expiration Validation (5 minute window)
        $timestamp = $request->header('X-Timestamp');
        if (!$timestamp || abs(now()->timestamp - (int) $timestamp) > 300) {
            $this->auditService->log(null, 'expired_webhook_timestamp', null, "Timestamp rejected: {$timestamp}");
            return response()->json(['error' => 'Expired Timestamp'], 401);
        }

        // 3. HMAC SHA256 Signature Validation
        $signature = $request->header('X-Signature');
        $secret = config('warehouse.courier_webhook_secret', 'testing-secret');
        $expectedSignature = hash_hmac('sha256', $request->getContent(), $secret);

        if (!hash_equals($expectedSignature, (string) $signature)) {
            $this->auditService->log(null, 'invalid_webhook_signature', null, "Invalid Signature from IP: {$request->ip()}");
            return response()->json(['error' => 'Invalid Signature'], 401);
        }

        // 4. Redis Idempotency Protection
        $webhookId = $request->header('X-Webhook-ID');
        if (!$webhookId) {
            return response()->json(['error' => 'Missing Webhook ID'], 400);
        }

        // Add to cache with 24 hour TTL. If it already exists, Cache::add returns false.
        if (!Cache::add("webhook_id_{$webhookId}", true, now()->addHours(24))) {
            $this->auditService->log(null, 'webhook_duplicate_ignored', null, "Duplicate payload ignored for ID {$webhookId} from IP {$request->ip()}");
            return response()->json(['message' => 'Duplicate payload acknowledged'], 200);
        }

        return $next($request);
    }
}
