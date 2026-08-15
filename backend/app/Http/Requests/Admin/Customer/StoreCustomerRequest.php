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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'phone_number' => 'nullable|string|regex:/^0[0-9]{9}$/',
            'password' => 'required|string|max:255',
            'status' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'Tên khách hàng không được để trống.',
            'last_name.required' => 'Họ khách hàng không được để trống.',
            'email.required' => 'Email không được để trống.',
            'email.email' => 'Email không hợp lệ.',
            'email.unique' => 'Email này đã tồn tại.',
            'password.required' => 'Mật khẩu không được để trống.',
            'status.required' => 'Trạng thái không được để trống.',
            'phone_number.regex' => 'Số điện thoại phải bao gồm 10 chữ số và bắt đầu bằng số 0.',
        ];
    }
}
