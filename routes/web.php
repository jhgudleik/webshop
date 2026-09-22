<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UserCrudController;
use App\Http\Controllers\Admin\ProductCrudController;
use Illuminate\Support\Facades\Auth;

Route::get('', [HomeController::class, 'index'])->name('home');

Route::prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::post('categories/move', [CategoryController::class, 'move'])->name('categories.move');
        Route::get('categories_tree', [CategoryController::class, 'tree'])->name('categories.tree');
        Route::resource('categories', CategoryController::class);
        Route::crud('users', UserCrudController::class);
        Route::crud('products', ProductCrudController::class);
    });


/*
|--------------------------------------------------------------------------
| Профиль пользователя
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // Страница профиля
    Route::get('profile', [ProfileController::class, 'profile'])
        ->name('profile');

    // Обновление имени, email и аватара
    Route::patch('profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    // Смена пароля
    Route::put('profile/password', [ProfileController::class, 'updatePassword'])
        ->name('profile.password');

    // Удаление аватара
    Route::delete('profile/avatar', [ProfileController::class, 'deleteAvatar'])
        ->name('profile.avatar.delete');
});


Auth::routes();