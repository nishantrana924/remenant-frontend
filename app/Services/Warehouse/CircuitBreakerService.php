<?php

namespace App\Services\Warehouse;

use Illuminate\Support\Facades\Cache;

class CircuitBreakerService
{
    private WarehouseAuditService $auditService;

    public function __construct(WarehouseAuditService $auditService)
    {
        $this->auditService = $auditService;
    }

    /**
     * Executes an API closure securely within the Circuit Breaker.
     * Throws exception if circuit is OPEN.
     */
    public function execute(string $serviceName, callable $action)
    {
        $state = $this->getState($serviceName);

        if ($state === 'open') {
            throw new \Exception("Circuit Breaker OPEN for {$serviceName}. Request blocked to protect upstream API.");
        }

        try {
            // Execute the external API call
            $result = $action();

            if ($state === 'half_open') {
                $this->reset($serviceName);
            }

            return $result;

        } catch (\Exception $e) {
            $this->recordFailure($serviceName);
            throw $e;
        }
    }

    private function getState(string $serviceName): string
    {
        $failures = Cache::get("circuit_breaker_{$serviceName}_failures", 0);
        $threshold = config('warehouse.circuit_breaker_failure_threshold', 5);
        $lockoutUntil = Cache::get("circuit_breaker_{$serviceName}_lockout");

        if ($lockoutUntil && now()->lessThan($lockoutUntil)) {
            return 'open';
        }

        if ($lockoutUntil && now()->greaterThanOrEqualTo($lockoutUntil)) {
            return 'half_open';
        }

        if ($failures >= $threshold) {
            $this->trip($serviceName);
            return 'open';
        }

        return 'closed';
    }

    private function recordFailure(string $serviceName): void
    {
        $failures = Cache::increment("circuit_breaker_{$serviceName}_failures") ?: 1;
        $threshold = config('warehouse.circuit_breaker_failure_threshold', 5);

        if ($failures == $threshold) {
            $this->trip($serviceName);
        }
    }

    private function trip(string $serviceName): void
    {
        $lockoutMinutes = config('warehouse.circuit_breaker_lockout_minutes', 10);
        Cache::put("circuit_breaker_{$serviceName}_lockout", now()->addMinutes($lockoutMinutes), now()->addMinutes($lockoutMinutes));
        
        $this->auditService->log(null, "circuit_breaker_open", "Circuit Breaker TRIPPED for {$serviceName} after consecutive failures.");
    }

    private function reset(string $serviceName): void
    {
        Cache::forget("circuit_breaker_{$serviceName}_failures");
        Cache::forget("circuit_breaker_{$serviceName}_lockout");
        
        $this->auditService->log(null, "circuit_breaker_closed", "Circuit Breaker RESET for {$serviceName}. Service recovered.");
    }
}
