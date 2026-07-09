<?php

namespace App\Http\Requests\Admin\Banner;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BannerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'         => ['required', 'string', 'max:255'],
            'image_url'     => ['required', 'string', 'max:500'],
            'target_url'    => ['nullable', 'string', 'max:500'],
            'position'      => ['required', 'string', 'max:100'],
            'display_order' => ['nullable', 'integer', 'min:0'],
            'is_active'     => ['required', 'boolean'],
            'start_date'    => ['nullable', 'date', 'required_with:end_date'],
            'end_date'      => ['nullable', 'date', 'after_or_equal:start_date'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'         => 'Tiêu đề banner không được để trống.',
            'title.max'              => 'Tiêu đề không được vượt quá 255 ký tự.',
            'image_url.required'     => 'URL hình ảnh không được để trống.',
            'image_url.max'          => 'URL hình ảnh không được vượt quá 500 ký tự.',
            'target_url.max'         => 'URL đích không được vượt quá 500 ký tự.',
            'position.required'      => 'Vị trí banner không được để trống.',
            'display_order.integer'  => 'Thứ tự hiển thị phải là số nguyên.',
            'display_order.min'      => 'Thứ tự hiển thị phải lớn hơn hoặc bằng 0.',
            'is_active.required'     => 'Trạng thái không được để trống.',
            'is_active.boolean'      => 'Trạng thái phải là true hoặc false.',
            'start_date.date'        => 'Ngày bắt đầu không hợp lệ.',
            'end_date.date'          => 'Ngày kết thúc không hợp lệ.',
            'end_date.after_or_equal'=> 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu.',
        ];
    }
}
