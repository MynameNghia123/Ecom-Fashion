<?php

namespace App\Http\Requests\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AttributeValueRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $attributeValueId = $this->route('attribute_value');

        return [
            'attribute_id' => ['required', 'exists:attributes,id'],
            'product_variant_id' => ['required', 'exists:product_variants,id'],
            'value' => [
                'required',
                'string',
                'max:255',
                Rule::unique('attribute_values')->where(function ($query) {
                    return $query->where('attribute_id', $this->attribute_id)
                        ->where('product_variant_id', $this->product_variant_id);
                })->ignore($attributeValueId),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'attribute_id.required' => 'Thuộc tính không được để trống.',
            'attribute_id.exists' => 'Thuộc tính không tồn tại.',
            'product_variant_id.required' => 'Biến thể sản phẩm không được để trống.',
            'product_variant_id.exists' => 'Biến thể sản phẩm không tồn tại.',
            'value.required' => 'Giá trị thuộc tính không được để trống.',
            'value.string' => 'Giá trị thuộc tính phải là chuỗi.',
            'value.max' => 'Giá trị thuộc tính không được vượt quá 255 ký tự.',
            'value.unique' => 'Giá trị thuộc tính này đã tồn tại cho biến thể này.',
        ];
    }
}
