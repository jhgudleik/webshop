<?php

use App\Http\Controllers\WEB\Admin\CategoryController;
use App\Http\Controllers\WEB\Admin\ProductCrudController;
use App\Http\Controllers\WEB\Admin\UserCrudController;
use App\Http\Controllers\WEB\HomeController;
use App\Http\Controllers\WEB\ProductsController;
use App\Http\Controllers\WEB\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('', [HomeController::class, 'index'])->name('home');

Route::get('products', [ProductsController::class, 'index'])->name('products.index');
Route::get('category/{category:slug}', [ProductsController::class, 'index'])->name('products.by-category');

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


// Auth routes (WEB namespace)
Route::namespace('App\Http\Controllers\WEB')->group(function () {
    Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('login', 'Auth\LoginController@login');
    Route::post('logout', 'Auth\LoginController@logout')->name('logout');
    Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
    Route::post('register', 'Auth\RegisterController@register');
    Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');
});
