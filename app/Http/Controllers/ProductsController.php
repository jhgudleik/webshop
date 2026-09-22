<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class ProductsController extends Controller
{
    public function index(?Category $category = null): View
    {
        $query = Product::query()
            ->where('active', true)
            ->with('category');

        if ($category) {
            $query->where('category_id', $category->id);
        }

        $products = $query->orderBy('title')->paginate(12);

        $categories = Category::query()
            ->whereNull('parent_id')
            ->where('active', true)
            ->with([
                'children' => function ($q) {
                    $q->where('active', true)->orderBy('sort_order');
                },
            ])
            ->orderBy('sort_order')
            ->get();

        return view('products.index', compact('products', 'categories', 'category'));
    }
}
