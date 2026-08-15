<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $categoryId = $this->route('category');

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')->ignore($categoryId),
            ],
            'description' => ['nullable', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'slug')->ignore($categoryId),
            ],
            'parent_id' => ['nullable', 'exists:categories,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên danh mục không được để trống.',
            'name.max' => 'Tên danh mục không được vượt quá 255 ký tự.',
            'name.unique' => 'Tên danh mục này đã tồn tại trong hệ thống.',
            'slug.required' => 'Slug danh mục không được để trống.',
            'slug.max' => 'Slug danh mục không được vượt quá 255 ký tự.',
            'slug.unique' => 'Slug danh mục này đã tồn tại trong hệ thống.',
            'parent_id.exists' => 'Danh mục cha không tồn tại.',
            'description.max' => 'Mô tả danh mục không được vượt quá 255 ký tự.',
        ];
    }
}
