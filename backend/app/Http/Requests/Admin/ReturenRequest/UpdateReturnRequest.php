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
            'order_id'              => ['required', 'integer', 'exists:orders,id'],
            'reason'                => ['required', 'string'],
            'evidence_images'       => ['nullable', 'array'],
            'evidence_images.*'     => ['string', 'max:255'],
            'status'                => ['required', 'string', 'in:pending,approved,rejected,completed'],
            'refund_amount'         => ['nullable', 'numeric', 'min:0'],
            'processed_by_staff_id' => ['nullable', 'integer', 'exists:staffs,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'order_id.required'              => 'Mã đơn hàng không được để trống.',
            'order_id.integer'               => 'Mã đơn hàng phải là số nguyên.',
            'order_id.exists'                => 'Đơn hàng không tồn tại trong hệ thống.',
            'reason.required'                => 'Lý do trả hàng không được để trống.',
            'reason.string'                  => 'Lý do trả hàng phải là chuỗi ký tự.',
            'evidence_images.array'          => 'Danh sách hình ảnh minh chứng phải là một mảng.',
            'evidence_images.*.string'       => 'Đường dẫn ảnh phải là chuỗi ký tự.',
            'evidence_images.*.max'          => 'Đường dẫn ảnh không được vượt quá 255 ký tự.',
            'status.required'                => 'Trạng thái xử lý không được để trống.',
            'status.in'                      => 'Trạng thái xử lý không hợp lệ (pending, approved, rejected, completed).',
            'refund_amount.numeric'          => 'Số tiền hoàn trả phải là số.',
            'refund_amount.min'              => 'Số tiền hoàn trả không được âm.',
            'processed_by_staff_id.integer'  => 'Mã nhân viên xử lý phải là số nguyên.',
            'processed_by_staff_id.exists'   => 'Nhân viên xử lý không tồn tại.',
        ];
    }
}
