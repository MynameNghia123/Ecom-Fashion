<?php

namespace App\Http\Requests\Client\Auth;

use Illuminate\Foundation\Http\FormRequest;

class VerifyOtpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'otp_code' => 'required|string|size:6',
        ];
    }

    public function messages(): array
    {
        return [
            'otp_code.required' => 'Mã OTP không được để trống.',
            'otp_code.size' => 'Mã OTP phải có đúng 6 chữ số.',
        ];
    }
}
