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
Route::get('/track-order/{order_number?}', [CheckoutController::class, 'track'])->name('order.track');
Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success')->middleware('auth');
Route::get('/order/{order}/invoice', [CheckoutController::class, 'invoice'])->name('order.invoice');


Route::get('/about', [\App\Http\Controllers\Public\AboutController::class, 'index'])->name('about');

 
Route::get('/contact', function () {
    return view('public.contact');
})->name('contact');

Route::get('/terms-and-conditions', function () {
    return view('public.terms');
})->name('terms');

Route::get('/privacy-policy', function () {
    return view('public.privacy');
})->name('privacy');

Route::get('/shipping-guide', function () {
    return view('public.shipping');
})->name('shipping');

Route::get('/refund-policy', function () {
    return view('public.refund');
})->name('refund');

Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/product/{slug}/reviews', [ProductController::class, 'reviews'])->name('products.reviews');

// Admin routes (all authenticated users are admins)




Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');
    Route::get('/my-orders', [DashboardController::class, 'user'])
        ->name('my-orders');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin routes
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function() { return redirect()->route('admin.dashboard'); });
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
    
    // Core Modules
    Route::post('categories/quick-add', [\App\Http\Controllers\Admin\CategoryController::class, 'quickAdd'])->name('categories.quick-add');
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
    Route::resource('sliders', \App\Http\Controllers\Admin\SliderController::class);
    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class);
    Route::post('orders/{id}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::resource('customers', \App\Http\Controllers\Admin\UserController::class);
    Route::post('customers/{id}/update-role', [\App\Http\Controllers\Admin\UserController::class, 'updateRole'])->name('customers.update-role');
    Route::get('inventory', [\App\Http\Controllers\Admin\InventoryController::class, 'index'])->name('inventory.index');
    Route::post('inventory/update', [\App\Http\Controllers\Admin\InventoryController::class, 'updateStock'])->name('inventory.update');

    // Editor Upload
    Route::post('editor-upload', [\App\Http\Controllers\Admin\UploadController::class, 'editorUpload'])->name('editor.upload');

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
});

require __DIR__.'/auth.php';
