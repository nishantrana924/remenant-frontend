# Warehouse Implementation Roadmap

This document mandates the strict 5-Phase strategy to deploy the Smart Warehouse Automation Engine safely using parallel execution.

## Pre-requisites
- The feature flag `warehouse_automation_mode` is set to `disabled` in `config/warehouse.php`.
- The new Courier Master table must be seeded with existing shipping partners.

## Phase 1: Database & Data Integrity (COMPLETED)
- Schema established, limits and tracking columns added.

## Phase 2: Engine Services & Business Logic (CURRENT)
**Goal**: Build the headless core automation engines via strict Incremental Development.

- **Step 1**: Build `WarehouseAuditService` and `PackagingCalculationService`.
- **Step 2**: Build `BatchingService` and `CourierRecommendationService`.
- **Step 3**: Build `BatchLockService` and `BatchValidationService`.
- **Step 4**: Build Observer Integration.
- **Step 5**: Build Queue Jobs.

**Constraints for Phase 2 Execution**:
- **Documentation First**: All actions must be documented. Tests documented in `docs/testing/warehouse/`.
- **Audit Requirement**: After every single step, an Audit Report must be generated and approved. No skipping steps.
- **Performance Budget**: Services must avoid N+1 queries, loops, and synchronous API calls. Eager loading and transactions are mandatory.
- **Production Safety**: Never modify existing shipments, never modify frozen batches, never regenerate AWBs automatically, and never bypass the Audit/Validation layers.

## Phase 3: Administrative Control Layer (UI)
The frontend UI integration is strictly governed by the "Thin Controllers" rule.
- **Phase 3A**: Controllers, Routes, Permissions, Policies, Request Validation.
- **Phase 3B**: Dashboard UI, Batch Screens, Manual Review UI, Monitoring UI.
- **Phase 3C**: Notifications, Health Dashboard, Audit Explorer.

## Phase 4: Active Fulfillment Execution (Jobs)
- Wiring the Jobs to the UI.

## Phase 5: Production Rollout (Zero Downtime)
- Cutover strategies via `.env` toggle.
