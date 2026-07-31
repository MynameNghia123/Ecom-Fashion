<?php

namespace App\Http\Requests\Admin\CustomerAddress;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerAddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id'    => 'required|exists:customers,id',
            'receiver_name'  => 'required|string|max:255',
            'receiver_phone' => 'required|string|digits:10',
            'province'       => 'nullable|string|max:100',
            'district'       => 'nullable|string|max:100',
            'ward'           => 'nullable|string|max:100',
            'detail_address' => 'required|string|max:255',
            'is_default'     => 'boolean',
        ];
    }
    
    public function messages(): array
    {
        return [
            'customer_id.required'    => 'Vui lòng chọn khách hàng.',
            'customer_id.exists'      => 'Khách hàng không tồn tại.',
            'receiver_name.required'  => 'Tên người nhận không được để trống.',
            'receiver_phone.required' => 'Số điện thoại không được để trống.',
            'receiver_phone.digits'   => 'Số điện thoại phải có đúng 10 chữ số.',
            'detail_address.required' => 'Địa chỉ chi tiết không được để trống.',
        ];
    }
}
