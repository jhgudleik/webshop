# Конспект: Структура проекта Laravel (Blade)


## 1. ОБЩАЯ СТРУКТУРА

laravel-project/

├── app/           // Ядро приложения (код)

├── bootstrap/     // Автозагрузка и стартовая загрузка

├── config/        // Все конфигурационные файлы

├── database/      // Миграции, сиды, фабрики

├── public/        // Корневая папка для веб-сервера

├── resources/     // Blade-шаблоны, ассеты, языковые файлы

├── routes/        // Определение всех маршрутов

├── storage/       // Логи, кэш, загруженные файлы

├── tests/         // Автотесты

├── vendor/        // Зависимости Composer

├── .env           // Настройки окружения

├── artisan        // CLI-инструмент

└── composer.json  // PHP-зависимости



## 2. КЛЮЧЕВЫЕ ДИРЕКТОРИИ

app/ — код приложения

├── Http/

│   ├── Controllers/   // Обработка запросов

│   ├── Middleware/    // Фильтры HTTP-запросов

│   └── Requests/      // Валидация форм

├── Models/            // Eloquent-модели для БД

└── Providers/         // Регистрация сервисов


Controller обычно:
получает запрос;
вызывает нужную бизнес-логику;
получает данные;
передаёт данные в представление;
возвращает ответ.

resources/ — представления

├── views/             // Blade-шаблоны (.blade.php)

│   ├── layouts/       // Базовые макеты страниц

│   ├── components/    // Переиспользуемые x-компоненты

│   ├── partials/      // Частичные фрагменты

│   └── pages/         // Цельные страницы

├── css/               // Стили (через Vite)

├── js/                // Скрипты (через Vite)

└── lang/              // Языковые строки

Здесь находятся ресурсы, которые используются для создания интерфейса.


routes/ — маршрутизация

├── web.php            // Веб-маршруты (CSRF, сессии)

└── console.php        // Artisan-команды

config/ — настройки

├── app.php            // Основные параметры

├── database.php       // Подключения БД

├── auth.php           // Аутентификация

├── cache.php          // Кэширование

├── mail.php           // SMTP-настройки

├── queue.php          // Очереди

└── filesystems.php    // Диски хранения

database/ — БД

├── migrations/        // Структура таблиц

├── seeders/           // Начальные данные

└── factories/         // Тестовые данные



## Вся цепочка Laravel + Blade

                 Laravel
                    │
                    ▼
             routes/web.php
                    │
                    ▼
               Middleware
                    │
                    ▼
                Controller
                    │
                    ▼
                  Model
                    │
                    ▼
                Database
                    │
                    ▼
                  Model
                    │
                    ▼
               Controller
                    │
                    ▼
              Blade View
                    │
                    ▼
                  HTML
                    │
                    ▼
                Browser       


## MVC = Model + View + Controller

                 БРАУЗЕР
                    │
                    │ GET /users
                    ▼
             routes/web.php
                    │
                    ▼
          UserController@index
                    │
                    │
             ┌──────┴──────┐
             │             │
             ▼             │
          User Model       │
             │             │
             ▼             │
          Database         │
             │             │
             │ users       │
             │             │
             └──────┬──────┘
                    │
                    │ данные
                    ▼
             UserController
                    │
                    ▼
          users/index.blade.php
                    │
                    ▼
                  HTML
                    │
                    ▼
                 БРАУЗЕР     

Laravel:
получает HTTP-запрос;
смотрит маршрут в routes/web.php;
запускает middleware;
вызывает Controller;
Controller может обратиться к Model;
Model получает данные из БД;
Controller передаёт данные в Blade;
Blade генерирует HTML;
Laravel отправляет HTML браузеру.

## Передача данных с бекенда на фронтенд в шаблонизатор Blade (в представление products.index).

### routes/web.php

```php
Route::get('/products', function () {
    $products = Product::all();

    return view('products.index', compact('products'));
});
```

### resources/views/products/index.blade.php
```php
<h1>Products</h1>

@foreach ($products as $product)
    <h2>{{ $product->name }}</h2>
    <p>{{ $product->description }}</p>
    <p>Цена: {{ $product->price }}</p>
    <p>Остаток: {{ $product->stock }}</p>
@endforeach
```

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
