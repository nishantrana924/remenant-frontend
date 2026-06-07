# Warehouse API & Service Flow

## Internal Service Architecture Flow

### 1. Order Approval Flow & Feature Flags
When an Admin approves an order (`status` -> `processing`), the `OrderObserver` checks `config('warehouse.automation_mode')`.
- `parallel`: Executes the legacy fulfillment operations, then dispatches `BatchingService::assignToBatch($order)`. It builds batches but halts before triggering actual API jobs, acting strictly as an analytical shadow system.

### 2. Batching Flow (`BatchingService`)
1. **Snapshot Freeze**: Reads `order_items` snapshots. If dimensions/weight are missing, flags `requires_manual_review = true` and halts.
2. **Signature Generation**: Generates human-readable signature.
3. **Batch Capacity Rules**: Checks current batch against `max_orders_per_batch`, `max_units_per_batch`, `max_weight_per_batch`, and `max_order_value_per_batch`. Splits into a new batch if limits are breached.
4. **Recommendation**: `CourierRecommendationService` maps snapshot weights to `shipping_rules`.

### 3. Locking Flow (`BatchLockService`)
Handles Simultaneous Admin Protection using `DB::transaction` + `lockForUpdate()`.

### 4. Admin Bulk Fulfillment Flows & `BatchValidationService`
All UI actions triggering Queue Jobs must first pass through `BatchValidationService::validate($batch, $targetStatus)`.
- **Validation**: Checks lock status, checks `awb_generated_at` (Idempotency), verifies order counts vs totals, and validates State Transition limits.
- **Execution**: If `BatchValidationService` passes, dispatches the Job (e.g., `ProcessBatchAWBJob`).

### 5. Audit Logging Flow (`WarehouseAuditService`)
Injected globally to enforce full timeline tracing in `warehouse_activity_logs`.
