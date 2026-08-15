<?php

namespace App\Http\Requests\Client\Coupon;

use Illuminate\Foundation\Http\FormRequest;

class ApplyCouponRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Anyone can apply coupon during checkout
    }

    public function rules(): array
    {
        return [
            'code' => 'required|string',
            'order_total' => 'required|numeric|min:0',
        ];
    }
}
