# Step 4 Observer Validation Tests

## 1. Automation Mode Enforcement
- **Test**: `test_disabled_mode_skips_processing`
- **Expected**: Observer explicitly returns before executing any logic if `config('warehouse.automation_mode') == 'disabled'`.
- **Test**: `test_parallel_mode_creates_shadow_batches_safely`
- **Expected**: Batching Service is called, but no external queue jobs are generated. Legacy flow is uninterrupted.
- **Test**: `test_enabled_mode_allows_downstream_execution`
- **Expected**: Full suite executes.

## 2. Idempotency Protection
- **Test**: `test_it_rejects_duplicate_processing_attempts`
- **Given**: An Order that already exists in `warehouse_batch_orders`.
- **Expected**: Observer detects active batch, skips processing, and audits `observer_skipped`.
- **Test**: `test_it_skips_cancelled_or_completed_orders`
- **Expected**: Returns gracefully and logs omission to `WarehouseActivityLog`.

## 3. Failure Isolation
- **Test**: `test_observer_exceptions_do_not_crash_order_checkout`
- **Given**: `BatchingService` throws a fatal database Exception (e.g., table missing).
- **Expected**: Catch block intercepts Exception, logs it via `Log::error`, audits it as `observer_failure`, and permits the original customer request to return HTTP 200/302 successfully.

## 4. Manual Review Routing
- **Test**: `test_it_routes_missing_address_to_manual_review`
- **Given**: Order with `shipping_address_id = null`.
- **Expected**: Observer sets `requires_manual_review = true`, preserves exact reason, audits `manual_review_routed`, and halts further automation.
