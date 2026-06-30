<?php
namespace App\Http\Requests\Admin\GoodReceipt;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateGoodReceiptRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $receiptId = $this->route('good_receipt'); 
        return [
            'receipt_code' => [
                'required', 
                'string', 
                'max:255', 
                Rule::unique('goods_receipts', 'receipt_code')->ignore($receiptId)
            ],
            
            'supplier_id'        => ['required', 'integer', 'exists:suppliers,id'],
            'staff_id'           => ['nullable', 'integer', 'exists:staff,id'], 
            'total_amount_price' => ['required', 'numeric', 'min:0'],
            'status'             => ['required', 'integer', 'in:0,1,2'], 
            'good_receipt_details'                      => ['nullable', 'array'],
            'good_receipt_details.*.product_variant_id' => ['required_with:good_receipt_details', 'integer', 'exists:product_variants,id'],
            'good_receipt_details.*.quantity'           => ['required_with:good_receipt_details', 'integer', 'min:1'],
            'good_receipt_details.*.import_price'       => ['required_with:good_receipt_details', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'receipt_code.required' => 'Mã phiếu nhập không được để trống.',
            'receipt_code.unique'   => 'Mã phiếu nhập này đã tồn tại ở một phiếu khác.',
            'supplier_id.exists'    => 'Nhà cung cấp không tồn tại.',
            'staff_id.exists'       => 'Nhân viên không tồn tại.',
            'total_amount_price.min'=> 'Tổng tiền không được là số âm.',
            'status.in'             => 'Trạng thái phiếu nhập không hợp lệ.',
            'good_receipt_details.array'                               => 'Chi tiết phiếu nhập phải là một mảng.',
            'good_receipt_details.*.product_variant_id.required_with'  => 'Biến thể sản phẩm không được để trống.',
            'good_receipt_details.*.product_variant_id.exists'         => 'Biến thể sản phẩm không tồn tại.',
            'good_receipt_details.*.quantity.required_with'            => 'Số lượng không được để trống.',
            'good_receipt_details.*.quantity.min'                      => 'Số lượng phải lớn hơn 0.',
            'good_receipt_details.*.import_price.required_with'        => 'Giá nhập không được để trống.',
            'good_receipt_details.*.import_price.min'                  => 'Giá nhập không được là số âm.',
        ];
    }
}