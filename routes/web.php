<?php

use App\Http\Controllers\Ajax\QuantityController;
use App\Http\Controllers\LangController;
use App\Http\Controllers\PharmacyController;
use App\Http\Controllers\PharmacyProductController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/language/{locale}', [LangController::class, 'switch'])->name('language.switch');

// Home route - redirect to products
Route::get('/', function () {
    return redirect()->route('products.index');
});

### to know the routes , php artisan route:list
// Product routes
Route::resource('products', ProductController::class);
Route::get('/search', [ProductController::class, 'search'])->name('products.search');

// Pharmacy routes
Route::resource('pharmacies', PharmacyController::class);

// Pharmacy Product Management routes
Route::prefix('pharmacies/{pharmacy}')->name('pharmacies.products.')->group(function () {
    Route::get('/products/add', [PharmacyProductController::class, 'create'])->name('create');
    Route::post('/products', [PharmacyProductController::class, 'store'])->name('store');
    Route::get('/products/{product}/edit', [PharmacyProductController::class, 'edit'])->name('edit');
    Route::put('/products/{product}', [PharmacyProductController::class, 'update'])->name('update');
    Route::delete('/products/{product}', [PharmacyProductController::class, 'destroy'])->name('destroy');
});


/*
|--------------------------------------------------------------------------
| AJAX Routes
|--------------------------------------------------------------------------
*/

Route::prefix('ajax')->name('ajax.')->group(function () {
    // Update product quantity
    Route::post('/products/{product}/quantity', [QuantityController::class, 'updateProductQuantity'])
        ->name('products.quantity');
    
    // Update pharmacy product quantity
    Route::post('/pharmacies/{pharmacy}/products/{product}/quantity', [QuantityController::class, 'updatePharmacyProductQuantity'])
        ->name('pharmacies.products.quantity');
});