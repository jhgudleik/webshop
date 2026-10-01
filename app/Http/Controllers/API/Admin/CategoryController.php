<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller

{

    public function index(): JsonResponse

    {

        return response()->json(Category::query()->paginate(10));

    }

    public function show(Category $category): JsonResponse

    {

        return response()->json($category);

    }

    public function store(Request $request): JsonResponse

    {

    }

    public function update(Request $request, Category $category): JsonResponse

    {

    }

    public function destroy(Category $category): JsonResponse

    {

    }

}

