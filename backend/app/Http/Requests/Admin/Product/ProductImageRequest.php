<?php

namespace App\Http\Requests\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;
// use Illuminate\Validation\Rule;

class ProductImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => ['required', 'exists:products,id'],
            'image_url' => ['required', 'string', 'max:2048'],
            'alt_text' => ['nullable', 'string', 'max:255'],
            'display_order' => ['nullable', 'integer', 'min:0'],
            'is_thumbnail' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'Sản phẩm không được để trống.',
            'product_id.exists' => 'Sản phẩm không tồn tại.',
            'image_url.required' => 'Đường dẫn hình ảnh không được để trống.',
            'image_url.max' => 'Đường dẫn hình ảnh không được vượt quá 2048 ký tự.',
            'alt_text.max' => 'Văn bản thay thế không được vượt quá 255 ký tự.',
            'display_order.integer' => 'Thứ tự hiển thị phải là số nguyên.',
            'display_order.min' => 'Thứ tự hiển thị phải lớn hơn hoặc bằng 0.',
            'is_thumbnail.boolean' => 'Giá trị ảnh đại diện phải là true hoặc false.',
        ];
    }
}
