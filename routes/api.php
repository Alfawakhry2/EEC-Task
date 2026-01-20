<?php

use App\Http\Controllers\Api\PharmacyController;
use App\Http\Controllers\Api\PharmacyProductController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Product CRUD (RESTful resource routes)
Route::apiResource('products', ProductController::class);

// Product search
Route::get('products-search', [ProductController::class, 'search']);


// Pharmacy CRUD (RESTful resource routes)
Route::apiResource('pharmacies', PharmacyController::class);


// Get available products for a pharmacy
Route::get('pharmacies/{pharmacy}/products', [PharmacyProductController::class, 'index']);

// Add product to pharmacy
Route::post('pharmacies/{pharmacy}/products', [PharmacyProductController::class, 'store']);

// Get product details in pharmacy (for editing)
Route::get('pharmacies/{pharmacy}/products/{product}', [PharmacyProductController::class, 'edit']);

// Update product in pharmacy
Route::put('pharmacies/{pharmacy}/products/{product}', [PharmacyProductController::class, 'update']);

// Remove product from pharmacy
Route::delete('pharmacies/{pharmacy}/products/{product}', [PharmacyProductController::class, 'destroy']);
