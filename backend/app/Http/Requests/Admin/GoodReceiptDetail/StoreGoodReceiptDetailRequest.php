<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGoodsReceiptDetailRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'goods_receipt_id'   => ['required', 'integer', 'exists:good_receipts,id'],
            'product_variant_id' => ['required', 'integer', 'exists:product_variants,id'],
            'quantity'           => ['required', 'integer', 'min:1'],
            'import_price'       => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'goods_receipt_id.exists'   => 'Phiếu nhập không tồn tại.',
            'product_variant_id.exists' => 'Sản phẩm không tồn tại.',
            'quantity.min'              => 'Số lượng nhập phải lớn hơn 0.',
            'import_price.min'          => 'Giá nhập không được nhỏ hơn 0.',
        ];
    }
}