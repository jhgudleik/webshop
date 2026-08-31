<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Models\Product;

Route::get('/test', function () {
    $users = User::all()->toArray();
    dd($users);
    return view('welcome');
});

Route::get('/', function () {
    dd('Hello, World!');
    return view('welcome');
 });

/* 
Route::get('/products', function () {
    $products = Product::all()->toArray();
    dd($products);
    return view('welcome'));
});
*/
// Передача данных с бекенда на фронтенд в шаблонизатор blade (в представление products.index).

Route::get('/products', function () {
    $products = Product::all();

    return view('products.index', compact('products'));
});

// Добавление продуктов в базу данных через маршрут
/*
Route::get('/add-products', function () {

    Product::create([
        'name' => 'iPhone 17',
        'description' => 'Новый смартфон Apple',
        'price' => 999.99,
        'stock' => 10,
        'is_active' => true,
    ]);

    Product::create([
        'name' => 'MacBook Pro',
        'description' => 'Мощный ноутбук Apple',
        'price' => 1999.99,
        'stock' => 5,
        'is_active' => true,
    ]);

    Product::create([
        'name' => 'AirPods Pro',
        'description' => 'Беспроводные наушники Apple',
        'price' => 249.99,
        'stock' => 20,
        'is_active' => true,
    ]);

    Product::create([
        'name' => 'Samsung Galaxy S26',
        'description' => 'Флагманский смартфон Samsung',
        'price' => 899.99,
        'stock' => 15,
        'is_active' => true,
    ]);

    Product::create([
        'name' => 'Sony WH-1000XM6',
        'description' => 'Беспроводные шумоподавляющие наушники',
        'price' => 399.99,
        'stock' => 8,
        'is_active' => false,
    ]);

    return 'Products added successfully!';
});
*/