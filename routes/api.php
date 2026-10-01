<?php

use App\Http\Controllers\API\Admin\CategoryController;
use App\Http\Controllers\API\Admin\ProductController;
use App\Http\Controllers\API\Auth\AuthController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('auth/login', [AuthController::class, 'login']);

// Protected routes (JWT)
Route::middleware('auth:api')->group(function () {

    // Auth
    Route::post('auth/logout', [AuthController::class, 'logout']);
    Route::post('auth/refresh', [AuthController::class, 'refresh']);
    Route::post('auth/me', [AuthController::class, 'me']);

    // Categories CRUD
    Route::prefix('categories')->name('api.categories.')->group(function () {
        Route::get('', [CategoryController::class, 'index'])->name('index');
        Route::get('{category}', [CategoryController::class, 'show'])->name('show');
        Route::post('', [CategoryController::class, 'store'])->name('store');
        Route::put('{category}', [CategoryController::class, 'update'])->name('update');
        Route::delete('{category}', [CategoryController::class, 'destroy'])->name('destroy');
    });

    // Products CRUD
    Route::prefix('products')->name('api.products.')->group(function () {
        Route::get('', [ProductController::class, 'index'])->name('index');
        Route::get('{product}', [ProductController::class, 'show'])->name('show');
        Route::post('', [ProductController::class, 'store'])->name('store');
        Route::put('{product}', [ProductController::class, 'update'])->name('update');
        Route::delete('{product}', [ProductController::class, 'destroy'])->name('destroy');
    });
});