<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(): JsonResponse
    {
        $products = Product::query()
            ->with('category')
            ->orderBy('id')
            ->paginate(12);

        return response()->json($products);
    }

    public function show(Product $product): JsonResponse
    {
        $product->load('category');

        return response()->json($product);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'title'       => 'required|string|max:255',
            'slug'        => 'nullable|string|max:200|unique:products,slug',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'quantity'    => 'required|integer|min:0',
            'active'      => 'boolean',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        $product = Product::create($validated);

        return response()->json([
            'message' => 'Товар создан.',
            'data'    => $product,
        ], 201);
    }

    public function update(Request $request, Product $product): JsonResponse
    {
        $validated = $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'title'       => 'required|string|max:255',
            'slug'        => ['nullable', 'string', 'max:200', Rule::unique('products')->ignore($product->id)],
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'quantity'    => 'required|integer|min:0',
            'active'      => 'boolean',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        $product->update($validated);

        return response()->json([
            'message' => 'Товар обновлён.',
            'data'    => $product,
        ]);
    }

    public function destroy(Product $product): JsonResponse
    {
        $product->delete();

        return response()->json([
            'message' => 'Товар удалён.',
        ]);
    }
}