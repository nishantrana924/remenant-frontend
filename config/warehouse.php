<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Warehouse Automation Mode
    |--------------------------------------------------------------------------
    |
    | Supported values:
    | - 'disabled': Existing fulfillment system only.
    | - 'parallel': Existing system + Warehouse Automation run together.
    | - 'enabled': Warehouse Automation becomes the primary fulfillment engine.
    |
    */
    'automation_mode' => env('WAREHOUSE_AUTOMATION_MODE', 'disabled'),

    /*
    |--------------------------------------------------------------------------
    | Batch Limits
    |--------------------------------------------------------------------------
    |
    | Automatically splits batches if they exceed these limits.
    |
    */
    'max_orders_per_batch' => env('WAREHOUSE_MAX_ORDERS_PER_BATCH', 50),
    'max_units_per_batch'  => env('WAREHOUSE_MAX_UNITS_PER_BATCH', 200),
    'max_weight_per_batch' => env('WAREHOUSE_MAX_WEIGHT_PER_BATCH', 25000), // in grams

    /*
    |--------------------------------------------------------------------------
    | Concurrency Protection
    |--------------------------------------------------------------------------
    |
    | Maximum time an admin can hold a batch lock before it automatically expires.
    |
    */
    'lock_timeout_minutes' => env('WAREHOUSE_LOCK_TIMEOUT_MINUTES', 15),

    /*
    |--------------------------------------------------------------------------
    | Queue Bulk Processing Limits
    |--------------------------------------------------------------------------
    */
    'max_awb_generation_batch_size' => env('WAREHOUSE_MAX_AWB_BATCH', 100),
    'max_label_generation_batch_size' => env('WAREHOUSE_MAX_LABEL_BATCH', 200),
    'max_pickup_batch_size' => env('WAREHOUSE_MAX_PICKUP_BATCH', 50),

    /*
    |--------------------------------------------------------------------------
    | API Circuit Breaker Settings
    |--------------------------------------------------------------------------
    */
    'circuit_breaker_failure_threshold' => 5,
    'circuit_breaker_lockout_minutes' => 10,

];
