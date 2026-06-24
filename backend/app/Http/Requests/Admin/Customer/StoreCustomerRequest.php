<?php

namespace App\Http\Requests\Admin\Customer;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name'   => 'required|string|max:255',
            'last_name'    => 'required|string|max:255',
            'email'        => 'required|email|unique:customers,email',
            'phone_number' => 'nullable|string|max:100',
            'password'     => 'required|string|max:255',
            'status'       => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required'   => 'Tên khách hàng không được để trống.',
            'last_name.required'    => 'Họ khách hàng không được để trống.',
            'email.required'        => 'Email không được để trống.',
            'email.email'           => 'Email không hợp lệ.',
            'email.unique'          => 'Email này đã tồn tại.',
            'password.required'     => 'Mật khẩu không được để trống.',
            'status.required'       => 'Trạng thái không được để trống.',
        ];
    }
}