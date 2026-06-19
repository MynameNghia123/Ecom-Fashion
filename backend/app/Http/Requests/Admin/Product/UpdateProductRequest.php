<?php

namespace App\Http\Requests\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Lấy ID của sản phẩm đang được update từ URL (ví dụ: route /products/{product})
        // Tùy thuộc vào cách bạn đặt tên tham số trên Route, thường là 'product'
        $productId = $this->route('product'); 
        // Nếu route model binding trả về Object, ta lấy ID:
        if (is_object($productId)) {
            $productId = $productId->id;
        }

        return [
            // ==========================================
            // 1. SẢN PHẨM CHÍNH
            // ==========================================
            'category_id' => 'required|integer|exists:categories,id',
            'name'        => 'required|string|max:255',
            // Check unique slug nhưng BỎ QUA ID của sản phẩm hiện tại
            'slug'        => "required|string|unique:products,slug,{$productId}",
            'description' => 'nullable|string',
            'brand'       => 'nullable|string|max:100',
            'thumbnail'   => 'nullable|url',
            'is_active'   => 'boolean',

            // ==========================================
            // 2. HÌNH ẢNH
            // ==========================================
            'images'             => 'nullable|array',
            'images.*.id'        => 'nullable|integer|exists:product_images,id', // Kèm theo ID để biết là update hay tạo mới
            'images.*.image_url' => 'required|url',
            'images.*.alt_text'  => 'nullable|string|max:100',

            // ==========================================
            // 3. BIẾN THỂ
            // ==========================================
            'variants'                  => 'required|array|min:1',
            'variants.*.id'             => 'nullable|integer|exists:product_variants,id',
            // Distinct giúp check không bị trùng SKU giữa các dòng Frontend vừa nhập
            // Closure giúp check Unique với Database
            'variants.*.sku'            => [
                'required',
                'string',
                'distinct',
                function ($attribute, $value, $fail) {
                    $index = explode('.', $attribute)[1];
                    $variantId = $this->input("variants.{$index}.id");

                    // Khởi tạo query tìm SKU này trong bảng product_variants
                    $query = DB::table('product_variants')->where('sku', $value);

                    // Nếu biến thể này đã có ID (đang update), ta BỎ QUA ID đó
                    if (!empty($variantId)) {
                        $query->where('id', '!=', $variantId);
                    }

                    // Nếu query tìm thấy dữ liệu -> báo lỗi
                    if ($query->exists()) {
                        $fail('Mã SKU này đã tồn tại trong hệ thống.');
                    }
                },
            ],
            'variants.*.price'          => 'required|numeric|min:0',
            'variants.*.stock_quantity' => 'required|integer|min:0',
            'variants.*.is_active'      => 'boolean',
            'variants.*.sale_price'     => [
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

            // ==========================================
            // 4. THUỘC TÍNH CỦA BIẾN THỂ
            // ==========================================
            'variants.*.attribute_values'                  => 'nullable|array',
            'variants.*.attribute_values.*.id'             => 'nullable|integer|exists:attribute_values,id',
            'variants.*.attribute_values.*.attribute_id' => 'required|integer|exists:attributes,id',
            'variants.*.attribute_values.*.value'        => 'required|string|max:50',
        ];
    }

    public function attributes(): array
    {
        return [
            'name'        => 'Tên sản phẩm',
            'category_id' => 'Danh mục',
            'slug'        => 'Đường dẫn tĩnh (Slug)',

            'images.*.id'        => 'ID Hình ảnh',
            'images.*.image_url' => 'Đường dẫn hình ảnh',
            
            'variants.*.id'             => 'ID Biến thể',
            'variants.*.sku'            => 'Mã SKU',
            'variants.*.price'          => 'Giá bán',
            'variants.*.sale_price'     => 'Giá khuyến mãi',
            'variants.*.stock_quantity' => 'Số lượng',
            
            'variants.*.attribute_values.*.id'           => 'ID Giá trị thuộc tính',
            'variants.*.attribute_values.*.attribute_id' => 'Thuộc tính',
            'variants.*.attribute_values.*.value'        => 'Giá trị',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute không được để trống.',
            'exists'   => ':attribute đang sửa không hợp lệ hoặc không tồn tại.',
            'max'      => ':attribute không được vượt quá :max ký tự.',
            'unique'   => ':attribute này đã tồn tại trong hệ thống.',
            
            'variants.*.sku.distinct' => ':attribute bị trùng lặp trong danh sách gửi lên.',
        ];
    }
}