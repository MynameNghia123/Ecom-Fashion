<?php

namespace App\Http\Requests\Admin\GoodReceipt;

use Illuminate\Foundation\Http\FormRequest;

class DeleteGoodReceiptRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $goodsReceipt = $this->route('goods_receipt');
            if ($goodsReceipt && in_array($goodsReceipt->status, ['cancel', 'completed'])) {
                $validator->errors()->add('status', 'Không thể xóa phiếu nhập đã bị hủy hoặc đã hoàn thành.');
            }
        });
    }
}
