<?php

use App\Http\Controllers\API\Admin\CategoryController;

use Illuminate\Support\Facades\Route;

Route::prefix('categories')->name('api.categories.')->group(function () {

    Route::get('', [CategoryController::class, 'index'])->name('index');

    Route::get('{category}', [CategoryController::class, 'show'])->name('show');

});

