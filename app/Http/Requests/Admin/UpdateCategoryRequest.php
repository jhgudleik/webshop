<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $category = $this->route('category');

        return [
            'title'     => 'required|string|max:255',
            'slug'      => ['required', 'string', 'max:100', Rule::unique('categories', 'slug')->ignore($category?->id)],
            'parent_id' => ['nullable', 'integer', 'exists:categories,id', Rule::notIn([$category?->id])],
            'active'    => 'nullable|boolean',
        ];
    }
}
