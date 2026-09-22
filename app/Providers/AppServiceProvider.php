<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        View::composer('*', function ($view) {
            if (!Schema::hasTable('categories')) {
                $view->with(['parentCategories' => collect()]);
                return;
            }

            $parentCategories = Category::query()
                ->whereNull('parent_id')
                ->where('active', true)
                ->with([
                    'children' => function ($query) {
                        $query
                            ->where('active', true)
                            ->orderBy('sort_order')
                            ->orderBy('title');
                    }
                ])
                ->orderBy('sort_order')
                ->orderBy('title')
                ->get();

            $view->with(['parentCategories' => $parentCategories]);
        });
    }
}
