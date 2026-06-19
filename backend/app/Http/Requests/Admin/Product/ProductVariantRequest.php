<?php

namespace App\Http\Requests\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductVariantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $variantId = $this->route('product_variant') ?? $this->route('variant');

        return [
            'product_id' => ['required', 'exists:products,id'],
            'sku' => [
                'required',
                'string',
                'max:255',
                Rule::unique('product_variants', 'sku')->ignore($variantId),
            ],
            'price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0', 'lte:price'],
            'cost_price' => ['nullable', 'numeric', 'min:0'],
            'stock_quantity' => ['nullable', 'integer', 'min:0'],
            'thumbnail' => ['nullable', 'string', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'Sản phẩm không được để trống.',
            'product_id.exists' => 'Sản phẩm không tồn tại.',
            'sku.required' => 'SKU biến thể không được để trống.',
            'sku.max' => 'SKU biến thể không được vượt quá 255 ký tự.',
            'sku.unique' => 'SKU biến thể này đã tồn tại trong hệ thống.',
            'price.required' => 'Giá bán không được để trống.',
            'price.numeric' => 'Giá bán phải là số.',
            'price.min' => 'Giá bán phải lớn hơn hoặc bằng 0.',
            'sale_price.numeric' => 'Giá khuyến mãi phải là số.',
            'sale_price.min' => 'Giá khuyến mãi phải lớn hơn hoặc bằng 0.',
            'sale_price.lte' => 'Giá khuyến mãi phải nhỏ hơn hoặc bằng giá bán gốc.',
            'cost_price.numeric' => 'Giá vốn phải là số.',
            'cost_price.min' => 'Giá vốn phải lớn hơn hoặc bằng 0.',
            'stock_quantity.integer' => 'Số lượng tồn kho phải là số nguyên.',
            'stock_quantity.min' => 'Số lượng tồn kho phải lớn hơn hoặc bằng 0.',
            'thumbnail.max' => 'Đường dẫn ảnh đại diện không được vượt quá 2048 ký tự.',
            'is_active.boolean' => 'Trạng thái hoạt động phải là true hoặc false.',
        ];
    }
}
