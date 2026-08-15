<?php

namespace App\Http\Requests\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // 1. SẢN PHẨM CHÍNH
            'category_id' => 'required|integer|exists:categories,id',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:products,slug',
            'description' => 'nullable|string',
            'brand' => 'nullable|string|max:100',
            'thumbnail' => 'nullable|url',
            'is_active' => 'boolean',

            // 2. HÌNH ẢNH
            'images' => 'nullable|array',
            'images.*.image_url' => 'nullable|url|max:2048',  // URL storage thật sau khi upload
            'images.*.alt_text' => 'nullable|string|max:100',
            'images.*.display_order' => 'nullable|integer|min:1',
            'images.*.is_thumbnail' => 'nullable|boolean',

            // 3. BIẾN THỂ
            'variants' => 'required|array|min:1',
            'variants.*.sku' => 'required|string|distinct|unique:product_variants,sku',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.stock_quantity' => 'required|integer|min:0',
            'variants.*.is_active' => 'boolean',
            'variants.*.cost_price' => 'nullable|numeric|min:0',
            'variants.*.thumbnail' => 'nullable|url|max:2048',
            'variants.*.sale_price' => [
                'nullable',
                'numeric',
                function ($attribute, $value, $fail) {
                    $index = explode('.', $attribute)[1];
                    $price = $this->input("variants.{$index}.price");

                    if ($price !== null && $value > $price) {
                        $fail('Giá khuyến mãi phải nhỏ hơn hoặc bằng giá bán gốc.');
                    }
                },
            ],

            // 4. THUỘC TÍNH
            'variants.*.attribute_values' => 'nullable|array',
            'variants.*.attribute_values.*.attribute_id' => 'required|integer|exists:attributes,id',
            'variants.*.attribute_values.*.value' => 'required|string|max:50',
        ];
    }

    public function attributes(): array
    {
        return [
            // Thêm Việt hóa cho phần Sản phẩm chính
            'name' => 'Tên sản phẩm',
            'category_id' => 'Danh mục',
            'slug' => 'Đường dẫn tĩnh (Slug)',

            'images.*.image_url' => 'Đường dẫn hình ảnh',

            'variants.*.sku' => 'Mã SKU',
            'variants.*.price' => 'Giá bán',
            'variants.*.sale_price' => 'Giá khuyến mãi',
            'variants.*.stock_quantity' => 'Số lượng',

            'variants.*.attribute_values.*.attribute_id' => 'Thuộc tính',
            'variants.*.attribute_values.*.value' => 'Giá trị',
        ];
    }

    public function messages(): array
    {
        return [
            // Thêm câu thông báo chung cho các lỗi hay gặp
            'required' => ':attribute không được để trống.',
            'unique' => ':attribute này đã tồn tại trong hệ thống.',
            'exists' => ':attribute không hợp lệ hoặc không tồn tại.',
            'max' => ':attribute không được vượt quá :max ký tự.',

            // Các câu thông báo riêng biệt (ghi đè)
            'variants.*.sku.distinct' => ':attribute bị trùng lặp trong danh sách gửi lên.',
        ];
    }
}
