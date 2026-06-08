# Step 1 Testing Requirements

## 1. `WarehouseAuditService`

### Success Scenario
- **Test**: `test_it_successfully_logs_an_action`
- **Given**: A valid `user_id` and action string (e.g., 'batch_created').
- **Expectation**: Record exists in `warehouse_activity_logs` with correct references. No exceptions thrown.

### Failure Scenario
- **Test**: `test_it_fails_safely_when_database_is_unreachable`
- **Given**: A disabled database connection or missing table.
- **Expectation**: Throws hard exception preventing parent transaction from committing (fulfilling the "No silent failures" rule).

### Edge Case Scenario
- **Test**: `test_it_logs_system_actions_without_user`
- **Given**: `userId` is null (e.g., automated cron job).
- **Expectation**: Record inserts gracefully with a null `user_id`, proving non-admin operations are still tracked.

---

## 2. `PackagingCalculationService`

### Success Scenario
- **Test**: `test_it_calculates_weight_and_dims_from_snapshots`
- **Given**: An `Order` with eager-loaded `orderItems` populated with valid snapshot weights and dims.
- **Expectation**: Accurately aggregates total weight and computes maximum base length/width and summed height without N+1 queries.

### Failure Scenario
- **Test**: `test_it_flags_missing_snapshot_data`
- **Given**: An `OrderItem` where `product_weight` is `null`.
- **Expectation**: Method instantly returns `['is_missing_data' => true]`. Ensures it never attempts a live fallback query to the `products` table.

### Edge Case Scenario
- **Test**: `test_it_handles_mixed_quantities_correctly`
- **Given**: An `OrderItem` with quantity `3` and another with quantity `1`.
- **Expectation**: Weight aggregates exactly `(item1_weight * 3) + item2_weight`. Height aggregates `(item1_height * 3) + item2_height`.
