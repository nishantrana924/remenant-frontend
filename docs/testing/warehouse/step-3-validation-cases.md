# Step 3 Validation Cases & Observability Rules

## 1. Success Cases
- **Test**: `test_it_passes_all_validations_for_clean_batch`
- **Given**: `status=pending`, correct lock owner, `total_order_value > 0`, `assigned_courier_id != null`, correct order counts.
- **Expectation**: `BatchValidationService::validateForJob` returns `void`. Queue job proceeds.

## 2. Failure Cases (Readiness)
- **Test**: `test_it_rejects_missing_assigned_courier`
- **Given**: Admin forgets to Bulk Assign Courier before generating AWB.
- **Expectation**: Throws Exception "Shipment readiness failed: Missing assigned courier." Audited.

## 3. Concurrency Cases
- **Test**: `test_it_blocks_simultaneous_admin_mutation`
- **Given**: Admin B calls an API endpoint while Admin A holds the lock.
- **Expectation**: HTTP 423 Locked Exception returned instantly. `lock_conflict_detected` audited.

## 4. Lock Timeout Cases
- **Test**: `test_it_auto_releases_stale_lock`
- **Given**: `locked_at` is `now()->subMinutes(16)`. Admin B accesses the batch.
- **Expectation**: Service detects `> 15m` config timeout. Silently nullifies `locked_by` on the DB, logs `lock_timeout_released`, and allows Admin B to acquire lock seamlessly.

## 5. Frozen Batch Cases
- **Test**: `test_it_protects_immutable_awb_batches`
- **Given**: Batch `status = awb_generated`. Admin attempts to change `assigned_weight`.
- **Expectation**: `enforceFrozenState()` throws Exception. `frozen_batch_violation` logged. Digital contract preserved.

---

## Observability Metrics Definitions (Future Implementation)
While no dashboard is built yet, the `WarehouseAuditService` action strings provide the literal dataset for these future TSDB/Grafana metrics:
- `lock_acquired_count`: `COUNT() WHERE action='batch_locked'`
- `lock_conflict_count`: `COUNT() WHERE action='lock_conflict_detected'`
- `lock_timeout_count`: `COUNT() WHERE action='lock_timeout_released'`
- `validation_failure_count`: `COUNT() WHERE action LIKE '%_validation_failed'`
- `frozen_batch_violation_count`: `COUNT() WHERE action='frozen_batch_violation'`
