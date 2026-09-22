<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Requests\Admin\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::query()
            ->with('parent')
            ->orderBy('title')
            ->paginate(20);

        return view('admin.categories.index', compact('categories'));
    }

    public function tree(): View
    {
        $parentCategories = Category::query()
            ->whereNull('parent_id')
            ->with('children')
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get();

        return view('admin.categories.tree', compact('parentCategories'));
    }

    public function create(): View
    {
        $categories = Category::query()
            ->orderBy('title')
            ->get();

        return view('admin.categories.create', compact('categories'));
    }

    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $parentId = $data['parent_id'] ?? null;

        $maxSortOrder = Category::query()
            ->where('parent_id', $parentId)
            ->max('sort_order');

        $data['sort_order'] = ($maxSortOrder ?? -1) + 1;

        Category::create($data);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Категория успешно создана.');
    }

    public function show(Category $category): View
    {
        $category->load(['parent', 'children']);

        return view('admin.categories.show', compact('category'));
    }

    public function edit(Category $category): View
    {
        $categories = Category::query()
            ->where('id', '!=', $category->id)
            ->orderBy('title')
            ->get();

        return view('admin.categories.edit', compact('category', 'categories'));
    }

    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        $data = $request->validated();

        if (
            array_key_exists('parent_id', $data) &&
            (int) $category->parent_id !== (int) ($data['parent_id'] ?? null)
        ) {
            $newParentId = $data['parent_id'] ?? null;

            $maxSortOrder = Category::query()
                ->where('parent_id', $newParentId)
                ->where('id', '!=', $category->id)
                ->max('sort_order');

            $data['sort_order'] = ($maxSortOrder ?? -1) + 1;
        }

        $category->update($data);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Категория успешно обновлена.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Категория успешно удалена.');
    }

    public function move(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'category_id' => 'required|integer|exists:categories,id',
            'parent_id'   => 'nullable|integer|exists:categories,id',
            'position'    => 'required|integer|min:0',
        ]);

        $category = Category::findOrFail($validated['category_id']);
        $newParentId = $validated['parent_id'];

        if ($newParentId !== null && (int) $newParentId === (int) $category->id) {
            return response()->json([
                'message' => 'Категория не может быть родителем самой себя.',
            ], 422);
        }

        if ($newParentId !== null) {
            $newParent = Category::findOrFail($newParentId);

            if ($this->isDescendant($category, $newParent)) {
                return response()->json([
                    'message' => 'Нельзя переместить категорию внутрь собственной дочерней категории.',
                ], 422);
            }
        }

        DB::transaction(function () use ($category, $newParentId, $validated) {
            $oldParentId = $category->parent_id;

            if ((int) $oldParentId !== (int) $newParentId) {
                $this->removeFromPosition($category);
                $category->update(['parent_id' => $newParentId]);
            }

            $siblings = Category::query()
                ->where('parent_id', $newParentId)
                ->where('id', '!=', $category->id)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get();

            $position = min($validated['position'], $siblings->count());

            $siblings->splice($position, 0, [$category]);

            foreach ($siblings as $index => $item) {
                $item->update(['sort_order' => $index]);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Категория перемещена.',
        ]);
    }

    private function removeFromPosition(Category $category): void
    {
        $siblings = Category::query()
            ->where('parent_id', $category->parent_id)
            ->where('id', '!=', $category->id)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        foreach ($siblings as $index => $item) {
            $item->update(['sort_order' => $index]);
        }
    }

    private function isDescendant(Category $category, Category $target): bool
    {
        $parentId = $target->parent_id;

        while ($parentId !== null) {
            if ((int) $parentId === (int) $category->id) {
                return true;
            }

            $parentId = Category::query()
                ->where('id', $parentId)
                ->value('parent_id');
        }

        return false;
    }
}
