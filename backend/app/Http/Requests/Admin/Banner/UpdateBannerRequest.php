<?php

namespace App\Http\Requests\Admin\Banner;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBannerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'         => ['required', 'string', 'max:255'],
            'image_url'     => ['required', 'string', 'max:255'],
            'target_url'    => ['nullable', 'string', 'max:255'],
            'position'      => ['required', 'string', 'max:100'],
            'display_order' => ['nullable', 'integer', 'min:0'],
            'is_active'     => ['nullable', 'boolean'],
            'start_date'    => ['nullable', 'date'],
            'end_date'      => ['nullable', 'date', 'after_or_equal:start_date'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'          => 'Tiêu đề banner không được để trống.',
            'title.string'            => 'Tiêu đề banner phải là chuỗi ký tự.',
            'title.max'               => 'Tiêu đề banner không được vượt quá 255 ký tự.',
            'image_url.required'      => 'Đường dẫn ảnh banner không được để trống.',
            'image_url.string'        => 'Đường dẫn ảnh banner phải là chuỗi ký tự.',
            'image_url.max'           => 'Đường dẫn ảnh banner không được vượt quá 255 ký tự.',
            'target_url.string'       => 'Đường dẫn đích phải là chuỗi ký tự.',
            'target_url.max'          => 'Đường dẫn đích không được vượt quá 255 ký tự.',
            'position.required'       => 'Vị trí hiển thị không được để trống.',
            'position.string'         => 'Vị trí hiển thị phải là chuỗi ký tự.',
            'position.max'            => 'Vị trí hiển thị không được vượt quá 100 ký tự.',
            'display_order.integer'   => 'Thứ tự hiển thị phải là số nguyên.',
            'display_order.min'       => 'Thứ tự hiển thị không được âm.',
            'is_active.boolean'       => 'Trạng thái hoạt động phải là kiểu boolean.',
            'start_date.date'         => 'Ngày bắt đầu không hợp lệ.',
            'end_date.date'           => 'Ngày kết thúc không hợp lệ.',
            'end_date.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu.',
        ];
    }
}
