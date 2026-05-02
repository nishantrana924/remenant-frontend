<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/about', function () {
    return view('public.about');
})->name('about');
 
Route::get('/contact', function () {
    return view('public.contact');
})->name('contact');

Route::get('/cart', function () {
    return view('public.cart');
})->name('cart');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/product/{slug}/reviews', [ProductController::class, 'reviews'])->name('products.reviews');

// Admin routes (all authenticated users are admins)
Route::get('/clear-cache', function() {
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    return "Cache cleared successfully!";
});

Route::get('/debug-db', [App\Http\Controllers\Admin\ArtisanController::class, 'debugDb']);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');
    Route::get('/setup', [\App\Http\Controllers\Admin\ArtisanController::class, 'setup'])->name('admin.setup');
    Route::get('/debug-db', [\App\Http\Controllers\Admin\ArtisanController::class, 'debugDb'])->name('admin.debug-db');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin routes
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function() { return redirect()->route('admin.dashboard'); });
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
    
    // Core Modules
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
    Route::resource('sliders', \App\Http\Controllers\Admin\SliderController::class);
    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class);
    Route::resource('customers', \App\Http\Controllers\Admin\UserController::class);
    Route::get('inventory', [\App\Http\Controllers\Admin\InventoryController::class, 'index'])->name('inventory.index');
    Route::post('inventory/update', [\App\Http\Controllers\Admin\InventoryController::class, 'updateStock'])->name('inventory.update');

    // Editor Upload
    Route::post('editor-upload', [\App\Http\Controllers\Admin\UploadController::class, 'editorUpload'])->name('editor.upload');

    // System Maintenance
    Route::get('migrate', [\App\Http\Controllers\Admin\ArtisanController::class, 'migrate'])->name('migrate');
    Route::get('seed', [\App\Http\Controllers\Admin\ArtisanController::class, 'seed'])->name('seed');
    Route::get('setup', [\App\Http\Controllers\Admin\ArtisanController::class, 'setup'])->name('setup');
});

require __DIR__.'/auth.php';
