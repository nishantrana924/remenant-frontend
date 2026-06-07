<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Warehouse\WarehouseDashboardController;
use App\Http\Controllers\Api\V1\Warehouse\WarehouseBatchController;
// Other controllers will be imported as needed...

Route::prefix('v1/warehouse')
    ->middleware(['auth:sanctum', 'verified']) // Assuming sanctum or auth logic
    ->group(function () {
        
        // Dashboard & Read APIs (60 requests/minute)
        Route::middleware('throttle:60,1')->group(function () {
            Route::get('dashboard', [WarehouseDashboardController::class, 'index'])->name('api.warehouse.dashboard');
            Route::get('monitoring', [WarehouseDashboardController::class, 'monitoring'])->name('api.warehouse.monitoring');
            Route::get('batches', [WarehouseBatchController::class, 'index'])->name('api.warehouse.batches.index');
            Route::get('batches/{batch}', [WarehouseBatchController::class, 'show'])->name('api.warehouse.batches.show');
            Route::get('manual-review', [\App\Http\Controllers\Api\V1\Warehouse\ManualReviewController::class, 'index'])->name('api.warehouse.manual-review.index');
        });
        
        // High Risk Mutation APIs (20 requests/minute)
        Route::middleware('throttle:20,1')->group(function () {
            Route::post('batches/{batch}/lock', [WarehouseBatchController::class, 'lock'])->name('api.warehouse.batches.lock');
            Route::post('batches/{batch}/unlock', [WarehouseBatchController::class, 'unlock'])->name('api.warehouse.batches.unlock');
            Route::post('batches/{batch}/awb', [WarehouseBatchController::class, 'generateAwb'])->name('api.warehouse.batches.awb');
            Route::post('batches/{batch}/assign-courier', [WarehouseBatchController::class, 'assignCourier'])->name('api.warehouse.batches.assign-courier');
            Route::post('batches/{batch}/assign-weight', [WarehouseBatchController::class, 'assignWeight'])->name('api.warehouse.batches.assign-weight');
            
            Route::post('manual-review/{order}/fix', [\App\Http\Controllers\Api\V1\Warehouse\ManualReviewController::class, 'fix'])->name('api.warehouse.manual-review.fix');
            Route::post('manual-review/{order}/revalidate', [\App\Http\Controllers\Api\V1\Warehouse\ManualReviewController::class, 'revalidate'])->name('api.warehouse.manual-review.revalidate');
        });
        
        // Bulk APIs (5 requests/minute)
        Route::middleware('throttle:5,1')->group(function () {
            Route::post('bulk/awb', [WarehouseBatchController::class, 'bulkGenerateAwb'])->name('api.warehouse.bulk.awb');
        });

});

// External Courier Webhooks (No Sanctum, HMAC Protected)
Route::prefix('v1/warehouse/webhooks')
    ->middleware([\App\Http\Middleware\VerifyCourierWebhook::class, 'throttle:60,1'])
    ->group(function () {
        Route::post('courier', [\App\Http\Controllers\Api\Warehouse\CourierWebhookController::class, 'handle'])->name('api.warehouse.webhooks.courier');
    });
