<?php

namespace App\Http\Requests\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $productId = $this->route('product');

        return [
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'slug')
                    ->ignore($productId)
                    ->whereNull('deleted_at'),
            ],
            'description' => ['nullable', 'string'],
            'brand' => ['nullable', 'string', 'max:255'],
            'thumbnail' => ['nullable', 'string', 'max:2048'],
            'user_manual' => ['nullable', 'string', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Danh mục sản phẩm không được để trống.',
            'category_id.exists' => 'Danh mục sản phẩm không tồn tại.',
            'name.required' => 'Tên sản phẩm không được để trống.',
            'name.max' => 'Tên sản phẩm không được vượt quá 255 ký tự.',
            'slug.required' => 'Slug sản phẩm không được để trống.',
            'slug.max' => 'Slug sản phẩm không được vượt quá 255 ký tự.',
            'slug.unique' => 'Slug sản phẩm đã tồn tại trong hệ thống.',
            'brand.max' => 'Tên thương hiệu không được vượt quá 255 ký tự.',
            'thumbnail.max' => 'Đường dẫn ảnh đại diện không được vượt quá 2048 ký tự.',
            'user_manual.max' => 'Đường dẫn hướng dẫn sử dụng không được vượt quá 2048 ký tự.',
            'is_active.boolean' => 'Trạng thái hoạt động phải là true hoặc false.',
        ];
    }
}
