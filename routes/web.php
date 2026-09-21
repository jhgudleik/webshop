<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UserCrudController;
use Illuminate\Support\Facades\Auth;

Route::get('', [HomeController::class, 'index'])->name('home');

Route::prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('categories', CategoryController::class);
        Route::resource('users', UserCrudController::class)->names('users');
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