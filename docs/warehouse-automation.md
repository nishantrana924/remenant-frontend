# Smart Warehouse Automation & Shipping Engine

## 1. Parallel Mode Deployment Strategy & Cutover
The system rolls out incrementally via the `warehouse_automation_mode` configuration:
- **`disabled`**: Only legacy fulfillment runs.
- **`parallel`**: Shadow Mode. The legacy fulfillment flow remains primary. The Warehouse Engine runs silently in the background to Calculate, Batch, Recommend, and Log. No external shipments or courier assignments occur. This allows Admins to safely compare the legacy flow against the Warehouse recommendation via KPIs (`total_orders_processed`, `batches_created`, `manual_review_count`, `courier_recommendation_accuracy`, `batching_accuracy`, `average_processing_time`).
- **`enabled`**: Cutover mode. Triggered only after defined success thresholds in parallel mode are validated.

## 2. Courier Master & Shipping Rules
- **Courier Master Table**: Standardizes shipping partners (`couriers`).
- **Shipping Rules**: Defines routing by mapping weight to `courier_id`.

## 3. Batch Capacity Rules & Splitting
To prevent physical warehouse bottlenecks during sales spikes, `BatchingService` dynamically splits incoming orders into new batches if they exceed strict configurable limits:
- `max_orders_per_batch`
- `max_units_per_batch`
- `max_weight_per_batch`
- `max_order_value_per_batch` (Added for insurance/value protections)
*Splits are heavily logged via the `WarehouseAuditService`.*

## 4. Product Snapshot Freeze Protection
Historical data immutability is absolute. Once an order is created, the system copies `product_name`, `product_sku`, `product_image`, `product_weight`, and dimensions into `order_items`. 
**Strict Rule**: The Engine operates *exclusively* on these snapshots. If any snapshot data is missing, the Engine refuses to compute defaults from the live `products` table and instead forces the order into `requires_manual_review = true`.

## 5. Batching Engine & Signatures
Orders are grouped by human-readable signatures. Batch types: `single_product`, `single_product_quantity`, `combo`, `multi_product`, `manual_review`.

## 6. Batch Validation & Health Monitoring
A dedicated `BatchValidationService` serves as the final safety gateway. Before any Queue Job executes, it strictly verifies:
1. Batch existence.
2. The batch is not locked by another admin.
3. The attempted state transition is valid.
4. The physical `orders` count mathematically matches the batch totals.
5. The assigned courier exists.
6. The AWB was not previously generated.

## 7. Batch Freeze Mechanism
Once a batch successfully reaches `status = awb_generated`, it enters an immutable Frozen State. Adding/removing orders or modifying weights is strictly prohibited to protect the Courier's digital contract.

## 8. AWB Idempotency Protection
Prevents duplicate API generation using `awb_generated_at` and `awb_job_uuid`. 

## 9. Transaction & Concurrency Protection
Every state mutation is protected by pessimistic locking (`DB::transaction` with `lockForUpdate()`).

## 10. Audit Logging
Every action must write through the `WarehouseAuditService`.
