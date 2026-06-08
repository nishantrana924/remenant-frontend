# Step 5 Queue Recovery & Observability Strategy

## 1. Queue Failure Recovery Rules
To protect the system against intermittent API failures, all Fulfillment Jobs are bound by the following recovery lifecycle:

- **Max Attempts**: Hardcapped at `3` attempts (`$tries = 3`) natively defined within each Job class.
- **Exponential Backoff**: Configured via the worker definition (e.g., `--backoff=30,60,120`). First retry at 30s, second at 60s, third at 120s.
- **Dead Letter Queue Strategy**: If a job fails its 3rd attempt, Laravel moves it to the `failed_jobs` table. The Batch status remains frozen in its last successful state (e.g., `awb_generated`). The `WarehouseAuditService` logs the fatal `job_failed` action.
- **Manual Recovery Procedure**: 
  1. An Admin reviews the `failed_jobs` exception trace.
  2. The Admin rectifies the underlying issue (e.g., replenishing NimbusPost wallet balance).
  3. The Admin clicks "Retry Failed Jobs" on the Dashboard, invoking `php artisan queue:retry {id}`.
  4. The Job resumes safely due to strict Idempotency protections.

## 2. Queue Observability Definitions (Future)
Future dashboards will aggregate these TSDB metrics based heavily on `warehouse_activity_logs` data:
- `awb_generation_success_rate`: `(COUNT(awb_generated) / COUNT(job_dispatched)) * 100`
- `awb_generation_failure_rate`: 100 - Success Rate
- `label_generation_success_rate`: Tracking successful Label generations.
- `pickup_success_rate`: Tracking successful Pickup scheduling.
- `average_job_runtime`: Future metric captured via Laravel Horizon.
- `queue_retry_count`: Tracked via Horizon or `failed_jobs` aggregations.
