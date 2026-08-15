<?php

namespace App\Http\Requests\Admin\Coupon;

use App\Enums\CouponType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CouponRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::enum(CouponType::class)],
            'discount_value' => ['required', 'numeric', 'min:0'],
            'price_min_order_value' => ['nullable', 'numeric', 'min:0'],
            'max_usage' => ['nullable', 'integer', 'min:1'],
            'is_active' => ['required', 'boolean'],
            'expiry_date' => ['nullable', 'date', 'after:today'],
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Mã code không được để trống.',
            'code.max' => 'Mã code không được vượt quá 255 ký tự.',
            'type.required' => 'Loại giảm giá không được để trống.',
            'type.in' => 'Loại giảm giá phải là percent hoặc fixed.',
            'discount_value.required' => 'Giá trị giảm không được để trống.',
            'discount_value.numeric' => 'Giá trị giảm phải là số.',
            'discount_value.min' => 'Giá trị giảm không được âm.',
            'price_min_order_value.numeric' => 'Giá trị đơn hàng tối thiểu phải là số.',
            'price_min_order_value.min' => 'Giá trị đơn hàng tối thiểu không được âm.',
            'max_usage.integer' => 'Số lần sử dụng tối đa phải là số nguyên.',
            'max_usage.min' => 'Số lần sử dụng tối đa phải lớn hơn 0.',
            'is_active.required' => 'Trạng thái không được để trống.',
            'is_active.boolean' => 'Trạng thái không hợp lệ.',
            'expiry_date.date' => 'Ngày hết hạn không hợp lệ.',
            'expiry_date.after' => 'Ngày hết hạn phải sau ngày hôm nay.',
        ];
    }
}
