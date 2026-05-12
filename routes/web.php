<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\ProductController;
use App\Http\Controllers\Public\CheckoutController;
use App\Http\Controllers\Public\CartController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout')->middleware('auth');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store')->middleware('auth');
Route::get('/checkout/payment/{order}', [CheckoutController::class, 'payment'])->name('checkout.payment')->middleware('auth');
Route::post('/checkout/payment/{order}/mock', [CheckoutController::class, 'mockPayment'])->name('checkout.payment.mock')->middleware('auth');
Route::get('/track-order/{order_number?}', [CheckoutController::class, 'track'])->name('order.track');
Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success')->middleware('auth');
Route::get('/order/{order}/invoice', [CheckoutController::class, 'invoice'])->name('order.invoice');


Route::get('/about', [\App\Http\Controllers\Public\AboutController::class, 'index'])->name('about');
 
Route::get('/contact', function () { 
    return view('public.contact');
})->name('contact');

Route::get('/terms-and-conditions', [\App\Http\Controllers\Public\LegalPageController::class, 'show'])->defaults('slug', 'terms-and-conditions')->name('terms');
Route::get('/privacy-policy', [\App\Http\Controllers\Public\LegalPageController::class, 'show'])->defaults('slug', 'privacy-policy')->name('privacy');
Route::get('/shipping-guide', [\App\Http\Controllers\Public\LegalPageController::class, 'show'])->defaults('slug', 'shipping-guide')->name('shipping');
Route::get('/refund-policy', [\App\Http\Controllers\Public\LegalPageController::class, 'show'])->defaults('slug', 'refund-policy')->name('refund');

Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/search-suggestions', [ProductController::class, 'searchSuggestions'])->name('products.search-suggestions');
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
    Route::post('orders/{id}/ship-to-shiprocket', [\App\Http\Controllers\Admin\OrderController::class, 'shipToShiprocket'])->name('orders.ship-to-shiprocket');
    Route::get('orders/{id}/invoice', [\App\Http\Controllers\Admin\OrderController::class, 'generateInvoice'])->name('orders.invoice');
    Route::get('orders/{id}/packing-slip', [\App\Http\Controllers\Admin\OrderController::class, 'generatePackingSlip'])->name('orders.packing-slip');
    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class);
    Route::post('orders/{id}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::get('shipping', [\App\Http\Controllers\Admin\ShippingController::class, 'index'])->name('shipping.index');
    Route::get('refunds', [\App\Http\Controllers\Admin\RefundController::class, 'index'])->name('refunds.index');
    Route::get('admins', [\App\Http\Controllers\Admin\UserController::class, 'admins'])->name('admins.index');
    Route::resource('customers', \App\Http\Controllers\Admin\UserController::class);
    Route::post('customers/{id}/update-role', [\App\Http\Controllers\Admin\UserController::class, 'updateRole'])->name('customers.update-role');
    Route::post('customers/{id}/restore', [\App\Http\Controllers\Admin\UserController::class, 'restore'])->name('customers.restore');
    
    // Reviews Management
    Route::post('reviews/bulk-action', [\App\Http\Controllers\Admin\ReviewController::class, 'bulkAction'])->name('reviews.bulk-action');
    Route::post('reviews/{id}/status', [\App\Http\Controllers\Admin\ReviewController::class, 'updateStatus'])->name('reviews.update-status');
    Route::post('reviews/{id}/toggle-featured', [\App\Http\Controllers\Admin\ReviewController::class, 'toggleFeatured'])->name('reviews.toggle-featured');
    Route::resource('reviews', \App\Http\Controllers\Admin\ReviewController::class)->only(['index', 'destroy']);

    Route::resource('coupons', \App\Http\Controllers\Admin\CouponController::class);
    Route::patch('coupons/{coupon}/toggle-status', [\App\Http\Controllers\Admin\CouponController::class, 'toggleStatus'])->name('coupons.toggle-status');
    Route::get('inventory', [\App\Http\Controllers\Admin\InventoryController::class, 'index'])->name('inventory.index');
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
});

require __DIR__.'/auth.php'; 