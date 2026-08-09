<?php

namespace App\Http\Requests\Admin\Order;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id'      => ['nullable', 'integer', 'exists:customers,id'],
            'shipping_name'    => ['required_without:customer_id', 'nullable', 'string', 'max:255'],
            'shipping_phone'   => ['required_without:customer_id', 'nullable', 'string', 'max:20'],
            'shipping_address' => ['required_without:customer_id', 'nullable', 'string', 'max:500'],
            
            'shipping_fee'     => ['required', 'numeric', 'min:0'],
            'discount_amount'  => ['nullable', 'numeric', 'min:0'],
            
            'payment_method'   => ['required', 'in:cod,vnpay,cash,bank_transfer'],
            'payment_status'   => ['required', 'in:pending,paid,failed'],
            'status'           => ['required', 'in:pending,processing,shipping,delivered,completed,cancelled'],
            
            'items'            => ['required', 'array', 'min:1'],
            'items.*.product_variant_id' => ['required', 'integer', 'exists:product_variants,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            
            'note'             => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'items.required' => 'Đơn hàng phải có ít nhất 1 sản phẩm.',
            'items.*.product_variant_id.exists' => 'Sản phẩm không hợp lệ hoặc đã bị xóa.',
            'shipping_name.required_without' => 'Vui lòng nhập tên người nhận nếu không chọn khách hàng.',
            'shipping_phone.required_without' => 'Vui lòng nhập số điện thoại người nhận nếu không chọn khách hàng.',
            'shipping_address.required_without' => 'Vui lòng nhập địa chỉ nếu không chọn khách hàng.',
        ];
    }
}
