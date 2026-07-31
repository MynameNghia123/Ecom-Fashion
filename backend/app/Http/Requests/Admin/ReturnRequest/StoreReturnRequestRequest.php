<?php
namespace App\Http\Requests\Admin\ReturnRequest;
use Illuminate\Foundation\Http\FormRequest;

class StoreReturnRequestRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array {
        return [
            'order_id'        => 'required|exists:orders,id',
            'order_detail_id' => 'nullable|exists:order_details,id',
            'reason'          => 'required|in:defective,wrong_size,wrong_item,change_mind,other',
            'customer_note'   => 'nullable|string|max:2000',
            'quantity'        => 'required|integer|min:1',
            'refund_amount'   => 'required|numeric|min:0',
            'evidence_images' => 'nullable|array',
        ];
    }
}
