<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'     => 'required|string|max:255',
            'slug'      => 'required|string|max:100|unique:categories,slug',
            'parent_id' => 'nullable|integer|exists:categories,id',
            'active'    => 'nullable|boolean',
        ];
    }
}
