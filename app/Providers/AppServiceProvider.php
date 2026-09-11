<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('layouts.main', function ($view) {
            $parentCategories = Category::query()
                ->whereNull('parent_id')
                ->where('active', true)
                ->with([
                    'children' => function ($query) {
                        $query
                            ->where('active', true)
                            ->orderBy('title');
                    },
                ])
                ->orderBy('title')
                ->get();

            $view->with('parentCategories', $parentCategories);
        });
    }
}