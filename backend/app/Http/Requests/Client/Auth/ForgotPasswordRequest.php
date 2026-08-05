<?php
namespace App\Http\Requests\Client\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ForgotPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|exists:customers,email',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Vui lòng cung cấp email.',
            'email.email'    => 'Email không hợp lệ.',
            'email.exists'   => 'Email này chưa được đăng ký trong hệ thống.'
        ];
    }
}
