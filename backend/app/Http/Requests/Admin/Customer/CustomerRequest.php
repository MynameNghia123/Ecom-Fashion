<?php

namespace App\Http\Requests\Admin\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $customer = $this->route('customer');
        $customerId = is_object($customer) ? $customer->id : $customer;

        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('customers', 'email')->ignore($customerId)],
            'phone_number' => ['required', 'string', 'max:255'],
            'password' => [$this->isMethod('post') ? 'required' : 'nullable', 'string', 'max:255'],
            'status' => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'Tên khách hàng không được để trống.',
            'first_name.max' => 'Tên khách hàng không được vượt quá 255 ký tự.',
            'last_name.required' => 'Họ khách hàng không được để trống.',
            'last_name.max' => 'Họ khách hàng không được vượt quá 255 ký tự.',
            'email.required' => 'Email khách hàng không được để trống.',
            'email.email' => 'Email khách hàng không hợp lệ.',
            'email.unique' => 'Email khách hàng đã tồn tại trong hệ thống.',
            'phone_number.required' => 'Số điện thoại khách hàng không được để trống.',
            'phone_number.max' => 'Số điện thoại khách hàng không được vượt quá 255 ký tự.',
            'password.required' => 'Mật khẩu khách hàng không được để trống.',
            'password.max' => 'Mật khẩu khách hàng không được vượt quá 255 ký tự.',
            'status.required' => 'Trạng thái khách hàng không được để trống.',
            'status.boolean' => 'Trạng thái khách hàng phải là true hoặc false.',
        ];
    }
}
