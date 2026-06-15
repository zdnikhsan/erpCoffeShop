<?php

use App\Http\Controllers\IngredientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Supplier — hanya owner & manager
    Route::resource('suppliers', SupplierController::class)
        ->middleware('role:owner|manager');

    // Products — hanya owner & manager
    Route::resource('products', ProductController::class)
        ->middleware('role:owner|manager');

    // Ingredients — hanya owner & manager bisa create/edit/delete
    Route::resource('ingredients', IngredientController::class)
        ->except(['index', 'show'])
        ->middleware('role:owner|manager');

    // Ingredients — owner, manager, cashier bisa lihat (index & show)
    Route::resource('ingredients', IngredientController::class)
        ->only(['index', 'show'])
        ->middleware('role:owner|manager|cashier');

    // Purchase Orders — hanya owner & manager
    Route::resource('purchase-orders', PurchaseOrderController::class)
        ->middleware('role:owner|manager');
    Route::patch('purchase-orders/{purchase_order}/status', [PurchaseOrderController::class, 'updateStatus'])
        ->name('purchase-orders.update-status')
        ->middleware('role:owner|manager');
});

require __DIR__.'/auth.php';
