# Warehouse Database Schema & ER Diagram

## ER Diagram Description

1. **`products` (1) --- (M) `order_items`**: 
   - `order_items` stores an enhanced snapshot (`product_name`, `product_sku`, `product_image`, `product_weight`, etc.) independent of live `products`.
2. **`couriers` (1) --- (M) `shipping_rules`**:
   - A courier partner has many rules.
3. **`couriers` (1) --- (M) `warehouse_batches`**:
   - A courier can be suggested or assigned to multiple batches.
4. **`warehouse_batches` (1) --- (M) `orders`**: 
   - Managed via Pivot table `warehouse_batch_orders`. 
5. **`users` (1) --- (M) `warehouse_batches`**: 
   - A user locks a batch (`locked_by`).
6. **`users`, `warehouse_batches`, `orders` (1) --- (M) `warehouse_activity_logs`**: 
   - Comprehensive audit trails.

## Table Definitions

### 1. `couriers` (NEW)
- **Columns**: `id`, `name`, `code`, `is_active` (boolean, default true), `timestamps`, `deleted_at`.

### 2. `products` (Modifications)
- **New Columns**: `default_weight`, `default_length`, `default_width`, `default_height` (nullable decimals).

### 3. `order_items` (Modifications for Snapshots)
- **New Columns**: `product_name`, `product_sku`, `product_image`, `product_weight`, `product_length`, `product_width`, `product_height`.

### 4. `orders` (Modifications)
- **New Columns**: `requires_manual_review` (boolean), `manual_review_reason` (string), `calculated_weight`, `override_weight`, `calculated_length`, `calculated_width`, `calculated_height`.
- **Indexes**: `INDEX(requires_manual_review)`

### 5. `shipping_rules` (NEW)
- **Columns**: `id`, `name`, `min_weight`, `max_weight`, `courier_id` (unsignedBigInteger, FK to couriers.id), `priority`, `is_active`, `timestamps`, `deleted_at`.
- **Foreign Keys**: `courier_id -> couriers.id` (ON DELETE RESTRICT).

### 6. `warehouse_batches` (NEW)
- **Columns**:
  - `id` (PK)
  - `batch_signature` (string)
  - `batch_type` (enum)
  - `status` (enum)
  - `suggested_courier_id` (FK to couriers.id, nullable)
  - `assigned_courier_id` (FK to couriers.id, nullable)
  - `assigned_weight` (decimal, nullable)
  - `total_orders` (integer)
  - `total_units` (integer)
  - `total_order_value` (decimal, default 0)
  - `estimated_shipping_cost` (decimal, nullable)
  - `actual_shipping_cost` (decimal, nullable)
  - `locked_by` (FK to users.id, nullable)
  - `locked_at` (timestamp, nullable)
  - `deleted_at`
  - `timestamps`
- **Foreign Keys**: `suggested_courier_id -> couriers.id` (SET NULL), `assigned_courier_id -> couriers.id` (SET NULL), `locked_by -> users.id` (SET NULL).
- **Indexes**: `INDEX(status, batch_type)`, `INDEX(batch_signature)`

### 7. `warehouse_batch_orders` (NEW)
- **Columns**: `batch_id` (FK), `order_id` (FK).
- **Foreign Keys**: `batch_id -> warehouse_batches.id` (CASCADE), `order_id -> orders.id` (CASCADE).
- **Indexes**: `PRIMARY(batch_id, order_id)`, `UNIQUE(order_id)`

### 8. `warehouse_activity_logs` (NEW)
- **Columns**: `id`, `user_id` (FK, nullable), `action`, `description`, `related_batch_id` (FK, nullable), `related_order_id` (FK, nullable), `timestamps`.
- **Foreign Keys**: `user_id -> users.id` (SET NULL), `related_batch_id -> warehouse_batches.id` (CASCADE), `related_order_id -> orders.id` (CASCADE).
- **Indexes**: `INDEX(related_batch_id)`, `INDEX(related_order_id)`, `INDEX(action, created_at)`
