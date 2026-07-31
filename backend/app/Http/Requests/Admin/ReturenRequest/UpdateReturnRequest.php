<?php

namespace App\Http\Requests\Admin\ReturenRequest;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReturnRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reason'                => ['nullable', 'string'],
            'evidence_images'       => ['nullable', 'array'],
            'evidence_images.*'     => ['string', 'max:500'],
            'status'                => ['required', 'string', 'in:pending,approved,rejected,completed'],
            'refund_amount'         => ['nullable', 'numeric', 'min:0'],
            'processed_by_staff_id' => ['nullable', 'integer', 'exists:staffs,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'status.required'                => 'Trạng thái xử lý không được để trống.',
            'status.in'                      => 'Trạng thái xử lý không hợp lệ (pending, approved, rejected, completed).',
            'evidence_images.*.max'          => 'Đường dẫn ảnh không được vượt quá 500 ký tự.',
            'refund_amount.numeric'          => 'Số tiền hoàn trả phải là số.',
            'refund_amount.min'              => 'Số tiền hoàn trả không được âm.',
            'processed_by_staff_id.integer'  => 'Mã nhân viên xử lý phải là số nguyên.',
            'processed_by_staff_id.exists'   => 'Nhân viên xử lý không tồn tại.',
        ];
    }
}
