<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\ProductController;
use App\Http\Controllers\Public\CheckoutController;
use App\Http\Controllers\Public\CartController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\NimbusWebhookController;

// ─── NimbusPost Webhook (CSRF-exempt, no auth required) ───────────────────────
// ─── NimbusPost Webhook (CSRF-exempt, no auth required) ───────────────────────
Route::post('/webhooks/nimbuspost', [NimbusWebhookController::class, 'handle'])
    ->middleware(\App\Http\Middleware\RateLimitNimbusWebhooks::class)
    ->name('webhooks.nimbuspost');
// ──────────────────────────────────────────────────────────────────────────────

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/sitemap.xml', [\App\Http\Controllers\Public\SitemapController::class, 'index']);
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout')->middleware('auth');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store')->middleware(['auth', 'throttle:10,1']);
Route::get('/checkout/payment/{order}', [CheckoutController::class, 'payment'])->name('checkout.payment')->middleware('auth');
Route::post('/webhooks/razorpay', [\App\Http\Controllers\RazorpayWebhookController::class, 'handle'])->name('webhooks.razorpay');
Route::post('/checkout/payment/verify', [CheckoutController::class, 'verifyPayment'])->name('checkout.payment.verify')->middleware(['auth', 'throttle:10,1']);
Route::get('/track-order/{order_number?}', [CheckoutController::class, 'track'])->name('order.track')->middleware('auth');
Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success')->middleware('auth');
Route::get('/order/{order}/invoice', [CheckoutController::class, 'invoice'])->name('order.invoice')->middleware('auth');
Route::post('/order/{order}/reorder', [CheckoutController::class, 'reorder'])->name('order.reorder')->middleware('auth');


Route::get('/about', [\App\Http\Controllers\Public\AboutController::class, 'index'])->name('about');
 
Route::get('/contact', function () { 
    return view('public.contact');
})->name('contact');
Route::post('/contact', [\App\Http\Controllers\Public\ContactController::class, 'store'])->name('contact.store')->middleware('throttle:5,1');

Route::get('/terms-and-conditions', [\App\Http\Controllers\Public\LegalPageController::class, 'show'])->defaults('slug', 'terms-and-conditions')->name('terms');
Route::get('/privacy-policy', [\App\Http\Controllers\Public\LegalPageController::class, 'show'])->defaults('slug', 'privacy-policy')->name('privacy');
Route::get('/shipping-guide', [\App\Http\Controllers\Public\LegalPageController::class, 'show'])->defaults('slug', 'shipping-guide')->name('shipping');
Route::get('/refund-policy', [\App\Http\Controllers\Public\LegalPageController::class, 'show'])->defaults('slug', 'refund-policy')->name('refund');

Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/search-suggestions', [ProductController::class, 'searchSuggestions'])->name('products.search-suggestions')->middleware('throttle:30,1');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/product/{slug}/reviews', [ProductController::class, 'reviews'])->name('products.reviews');
Route::post('/product/{id}/reviews', [ProductController::class, 'storeReview'])->name('products.reviews.store');

// Coupon Routes
Route::post('/coupons/apply', [\App\Http\Controllers\Public\CouponController::class, 'apply'])->name('coupons.apply');
Route::get('/coupons/apply', function() { return redirect()->back()->with('error', 'Direct access not allowed.'); });

// Admin routes (all authenticated users are admins)







Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');
    Route::get('/my-orders', [DashboardController::class, 'user'])
        ->name('my-orders');
    Route::post('/orders/{order}/cancel', [DashboardController::class, 'cancelOrder'])
        ->name('order.cancel');
    Route::post('/orders/{order}/return-request', [CheckoutController::class, 'requestReturn'])
        ->name('order.return-request');

    Route::get('/profile', function() { return redirect()->route('my-orders', ['tab' => 'profile']); })->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Address Management
    Route::post('/addresses', [\App\Http\Controllers\Public\AddressController::class, 'store'])->name('addresses.store');
    Route::put('/addresses/{address}', [\App\Http\Controllers\Public\AddressController::class, 'update'])->name('addresses.update');
    Route::delete('/addresses/{address}', [\App\Http\Controllers\Public\AddressController::class, 'destroy'])->name('addresses.destroy');
    Route::post('/addresses/{address}/default', [\App\Http\Controllers\Public\AddressController::class, 'setDefault'])->name('addresses.set-default');
});

// Admin routes
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function() { return redirect()->route('admin.dashboard'); });
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
    
    // Core Modules
    Route::post('categories/quick-add', [\App\Http\Controllers\Admin\CategoryController::class, 'quickAdd'])->name('categories.quick-add');
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
    Route::post('products/bulk-delete', [\App\Http\Controllers\Admin\ProductController::class, 'bulkDestroy'])->name('products.bulk-destroy');
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
    Route::resource('sliders', \App\Http\Controllers\Admin\SliderController::class);
    Route::patch('sliders/{slider}/toggle-status', [\App\Http\Controllers\Admin\SliderController::class, 'toggleStatus'])->name('sliders.toggle-status');
    Route::post('orders/bulk-update-status', [\App\Http\Controllers\Admin\OrderController::class, 'bulkUpdateStatus'])->name('orders.bulk-update-status');
    Route::post('orders/bulk-delete', [\App\Http\Controllers\Admin\OrderController::class, 'bulkDestroy'])->name('orders.bulk-delete');
    Route::post('orders/bulk-ship-to-nimbuspost', [\App\Http\Controllers\Admin\OrderController::class, 'bulkShipToNimbusPost'])->name('orders.bulk-ship-to-nimbuspost');
    Route::post('orders/bulk-cancel-nimbuspost', [\App\Http\Controllers\Admin\OrderController::class, 'bulkCancelNimbusPost'])->name('orders.bulk-cancel-nimbuspost');
    Route::get('orders/fetch-couriers', [\App\Http\Controllers\Admin\OrderController::class, 'fetchCouriers'])->name('orders.fetch-couriers');
    Route::get('orders/bulk-packing-slips', [\App\Http\Controllers\Admin\OrderController::class, 'bulkPackingSlips'])->name('orders.bulk-packing-slips');
    Route::get('orders/bulk-shipping-labels', [\App\Http\Controllers\Admin\OrderController::class, 'bulkShippingLabels'])->name('orders.bulk-shipping-labels');
    Route::post('orders/{id}/ship-to-nimbuspost', [\App\Http\Controllers\Admin\OrderController::class, 'shipToNimbusPost'])->name('orders.ship-to-nimbuspost');
    Route::post('orders/{id}/nimbuspost-rates', [\App\Http\Controllers\Admin\OrderController::class, 'fetchNimbusRates'])->name('orders.nimbuspost-rates');
    Route::post('orders/{id}/cancel-nimbuspost', [\App\Http\Controllers\Admin\OrderController::class, 'cancelNimbusPost'])->name('orders.cancel-nimbuspost');
    Route::post('orders/bulk-pickup', [\App\Http\Controllers\Admin\OrderController::class, 'bulkPickup'])->name('orders.bulk-pickup');
    Route::get('orders/{id}/nimbus-label', [\App\Http\Controllers\Admin\OrderController::class, 'generateNimbusLabel'])->name('orders.nimbus-label');
    Route::get('orders/{id}/invoice', [\App\Http\Controllers\Admin\OrderController::class, 'generateInvoice'])->name('orders.invoice');
    Route::get('orders/{id}/packing-slip', [\App\Http\Controllers\Admin\OrderController::class, 'generatePackingSlip'])->name('orders.packing-slip');
    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class);
    Route::post('orders/{id}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::post('orders/{id}/approve-cancellation', [\App\Http\Controllers\Admin\OrderController::class, 'approveCancellation'])->name('orders.approve-cancellation');
    Route::post('orders/{id}/reject-cancellation', [\App\Http\Controllers\Admin\OrderController::class, 'rejectCancellation'])->name('orders.reject-cancellation');
    Route::post('orders/{id}/sync-refund', [\App\Http\Controllers\Admin\OrderController::class, 'syncRefund'])->name('orders.sync-refund');
    Route::post('orders/{id}/approve-return', [\App\Http\Controllers\Admin\ReturnController::class, 'approveReturn'])->name('orders.approve-return');
    Route::post('orders/{id}/reject-return', [\App\Http\Controllers\Admin\ReturnController::class, 'rejectReturn'])->name('orders.reject-return');
    Route::get('shipping', [\App\Http\Controllers\Admin\ShippingController::class, 'index'])->name('shipping.index');
    Route::get('refunds', [\App\Http\Controllers\Admin\RefundController::class, 'index'])->name('refunds.index');
    Route::get('admins', [\App\Http\Controllers\Admin\UserController::class, 'admins'])->name('admins.index');
    Route::post('admins', [\App\Http\Controllers\Admin\UserController::class, 'storeAdmin'])->name('admins.store');
    Route::resource('customers', \App\Http\Controllers\Admin\UserController::class);
    Route::post('customers/{id}/update-role', [\App\Http\Controllers\Admin\UserController::class, 'updateRole'])->name('customers.update-role');
    Route::post('customers/{id}/restore', [\App\Http\Controllers\Admin\UserController::class, 'restore'])->name('customers.restore');
    Route::delete('customers/{id}/force-delete', [\App\Http\Controllers\Admin\UserController::class, 'forceDelete'])->name('customers.force-delete');
    
    // Reviews Management
    Route::post('reviews/bulk-action', [\App\Http\Controllers\Admin\ReviewController::class, 'bulkAction'])->name('reviews.bulk-action');
    Route::post('reviews/{id}/status', [\App\Http\Controllers\Admin\ReviewController::class, 'updateStatus'])->name('reviews.update-status');
    Route::post('reviews/{id}/toggle-featured', [\App\Http\Controllers\Admin\ReviewController::class, 'toggleFeatured'])->name('reviews.toggle-featured');
    Route::patch('reviews/{id}/update-field', [\App\Http\Controllers\Admin\ReviewController::class, 'updateField'])->name('reviews.update-field');
    Route::resource('reviews', \App\Http\Controllers\Admin\ReviewController::class)->only(['index', 'destroy']);

    Route::resource('coupons', \App\Http\Controllers\Admin\CouponController::class);
    Route::patch('coupons/{coupon}/toggle-status', [\App\Http\Controllers\Admin\CouponController::class, 'toggleStatus'])->name('coupons.toggle-status');
    Route::get('inventory', [\App\Http\Controllers\Admin\InventoryController::class, 'index'])->name('inventory.index');
    Route::get('inventory/logs', [\App\Http\Controllers\Admin\InventoryController::class, 'logs'])->name('inventory.logs');
    Route::post('inventory/update', [\App\Http\Controllers\Admin\InventoryController::class, 'updateStock'])->name('inventory.update');
    Route::post('products/preview', [\App\Http\Controllers\Public\ProductController::class, 'preview'])->name('products.preview');

    // About Page Editor
    Route::get('about', [\App\Http\Controllers\Admin\AboutController::class, 'edit'])->name('about.edit');
    Route::post('about', [\App\Http\Controllers\Admin\AboutController::class, 'update'])->name('about.update');
    Route::post('about/preview', [\App\Http\Controllers\Public\AboutController::class, 'preview'])->name('about.preview');
    Route::post('about/restore/{id}', [\App\Http\Controllers\Admin\AboutController::class, 'restore'])->name('about.restore');

    // Legal Pages
    Route::get('legal', [\App\Http\Controllers\Admin\LegalPageController::class, 'index'])->name('legal.index');
    Route::get('legal/{id}/edit', [\App\Http\Controllers\Admin\LegalPageController::class, 'edit'])->name('legal.edit');
    Route::put('legal/{id}', [\App\Http\Controllers\Admin\LegalPageController::class, 'update'])->name('legal.update');

    // Contact Messages
    Route::get('contact-messages', [\App\Http\Controllers\Admin\ContactMessageController::class, 'index'])->name('contact-messages.index');
    Route::get('contact-messages/{id}', [\App\Http\Controllers\Admin\ContactMessageController::class, 'show'])->name('contact-messages.show');
    Route::delete('contact-messages/{id}', [\App\Http\Controllers\Admin\ContactMessageController::class, 'destroy'])->name('contact-messages.destroy');

    // Editor Upload
    Route::post('editor-upload', [\App\Http\Controllers\Admin\UploadController::class, 'editorUpload'])->name('editor.upload');
    Route::post('upload', [\App\Http\Controllers\Admin\UploadController::class, 'upload'])->name('upload');
    Route::get('media-list', [\App\Http\Controllers\Admin\UploadController::class, 'list'])->name('media-list');

    // System Maintenance
    Route::get('migrate', [\App\Http\Controllers\Admin\ArtisanController::class, 'migrate'])->name('migrate');
    Route::get('seed', [\App\Http\Controllers\Admin\ArtisanController::class, 'seed'])->name('seed');
    Route::get('setup', [\App\Http\Controllers\Admin\ArtisanController::class, 'setup'])->name('setup');
    Route::get('debug-db', [\App\Http\Controllers\Admin\ArtisanController::class, 'debugDb'])->name('debug-db');
    Route::get('clear-cache', function() {
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        return "Cache cleared successfully!";
    })->name('clear-cache');
    Route::get('debug-db', [\App\Http\Controllers\Admin\ArtisanController::class, 'debugDb'])->name('debug-db');
    Route::get('clear-cache', function() {
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        return "Cache cleared successfully!";
    })->name('clear-cache');
    // Logistics Module
    Route::prefix('logistics')->name('logistics.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\ShippingController::class, 'dashboard'])->name('dashboard');
        Route::post('/sync-all', [\App\Http\Controllers\Admin\ShippingController::class, 'syncAll'])->name('sync-all');
        Route::get('/orders', [\App\Http\Controllers\Admin\ShippingController::class, 'index'])->name('index');
        Route::get('/all-shipments', [\App\Http\Controllers\Admin\ShippingController::class, 'allShipments'])->name('all-shipments');
        Route::get('/shipment-details/{id}', [\App\Http\Controllers\Admin\ShippingController::class, 'showShipment'])->name('shipment-details');
        Route::post('/calculate-rates', [\App\Http\Controllers\Admin\ShippingController::class, 'calculateRates'])->name('calculate-rates');
        Route::post('/create-shipment/{orderId}', [\App\Http\Controllers\Admin\ShippingController::class, 'createShipment'])->name('create-shipment');
        Route::post('/cancel-shipment/{id}', [\App\Http\Controllers\Admin\ShippingController::class, 'cancelShipment'])->name('cancel-shipment');
        Route::get('/track/{awb}', [\App\Http\Controllers\Admin\ShippingController::class, 'track'])->name('track');
        Route::post('/bulk-labels', [\App\Http\Controllers\Admin\ShippingController::class, 'bulkLabels'])->name('bulk-labels');

        Route::resource('warehouses', \App\Http\Controllers\Admin\WarehouseController::class);
        Route::post('warehouses/sync-from-nimbus', [\App\Http\Controllers\Admin\WarehouseController::class, 'syncFromNimbus'])->name('warehouses.sync');
        Route::post('warehouses/{id}/set-default', [\App\Http\Controllers\Admin\WarehouseController::class, 'setDefault'])->name('warehouses.set-default');

        Route::get('/ndr', [\App\Http\Controllers\Admin\LogisticsController::class, 'ndr'])->name('ndr');
        Route::post('/ndr/action', [\App\Http\Controllers\Admin\LogisticsController::class, 'ndrAction'])->name('ndr.action');
        Route::get('/logs', [\App\Http\Controllers\Admin\LogisticsController::class, 'logs'])->name('logs');
    });

    // Settings
    Route::get('settings/general', [\App\Http\Controllers\Admin\SettingController::class, 'general'])->name('settings.general');
    Route::post('settings/general', [\App\Http\Controllers\Admin\SettingController::class, 'updateGeneral'])->name('settings.general.update');
    Route::get('settings/invoice', [\App\Http\Controllers\Admin\SettingController::class, 'invoice'])->name('settings.invoice');
    Route::post('settings/invoice', [\App\Http\Controllers\Admin\SettingController::class, 'updateInvoice'])->name('settings.invoice.update');
    Route::get('settings/shipping', [\App\Http\Controllers\Admin\SettingController::class, 'shipping'])->name('settings.shipping');
    Route::post('settings/shipping', [\App\Http\Controllers\Admin\SettingController::class, 'updateShipping'])->name('settings.shipping.update');
});

require __DIR__.'/auth.php';
