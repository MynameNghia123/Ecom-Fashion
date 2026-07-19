<?php

namespace App\Http\Requests\Admin\Order;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $order   = $this->route('order');
        $orderId = is_object($order) ? $order->id : $order;

        return [
            'order_code'                         => ['required', 'string', 'max:255', Rule::unique('orders', 'order_code')->ignore($orderId)],
            'customer_id'                        => ['required', 'integer', 'exists:customers,id'],
            'coupon_id'                          => ['nullable', 'integer', 'exists:coupons,id'],
            'shipping_name'                      => ['required', 'string', 'max:255'],
            'shipping_phone'                     => ['required', 'string', 'max:255'],
            'shipping_address'                   => ['required', 'string', 'max:255'],
            'sub_total_amount'                   => ['nullable', 'numeric', 'min:0'],
            'coupon_discount_amount'             => ['nullable', 'numeric', 'min:0'],
            'shipping_fee'                       => ['nullable', 'numeric', 'min:0'],
            'final_amount'                       => ['nullable', 'numeric', 'min:0'],
            'status'                             => ['nullable', 'string', 'max:50'],
            'payment_method'                     => ['required', 'string', 'max:50'],
            'payment_status'                     => ['nullable', 'string', 'max:50'],
            'transaction_id'                     => ['nullable', 'string', 'max:255'],
            'order_details'                      => ['nullable', 'array'],
            'order_details.*.product_variant_id' => ['required_with:order_details', 'integer', 'exists:product_variants,id'],
            'order_details.*.quantity'           => ['required_with:order_details', 'integer', 'min:1'],
            'order_details.*.unit_price'         => ['required_with:order_details', 'numeric', 'min:0'],
            'order_details.*.cost_price'         => ['nullable', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'order_code.required'                               => 'Mã đơn hàng không được để trống.',
            'order_code.string'                                 => 'Mã đơn hàng phải là chuỗi ký tự.',
            'order_code.max'                                    => 'Mã đơn hàng không được vượt quá 255 ký tự.',
            'order_code.unique'                                 => 'Mã đơn hàng này đã tồn tại trong hệ thống.',
            'customer_id.required'                              => 'Khách hàng không được để trống.',
            'customer_id.integer'                               => 'ID khách hàng phải là số nguyên.',
            'customer_id.exists'                                => 'Khách hàng không tồn tại trong hệ thống.',
            'coupon_id.integer'                                 => 'Mã giảm giá phải là số nguyên.',
            'coupon_id.exists'                                  => 'Mã giảm giá không tồn tại.',
            'shipping_name.required'                            => 'Tên người nhận không được để trống.',
            'shipping_name.string'                              => 'Tên người nhận phải là chuỗi ký tự.',
            'shipping_name.max'                                 => 'Tên người nhận không được vượt quá 255 ký tự.',
            'shipping_phone.required'                           => 'Số điện thoại người nhận không được để trống.',
            'shipping_phone.string'                             => 'Số điện thoại người nhận phải là chuỗi ký tự.',
            'shipping_phone.max'                                => 'Số điện thoại người nhận không được vượt quá 255 ký tự.',
            'shipping_address.required'                         => 'Địa chỉ giao hàng không được để trống.',
            'shipping_address.string'                           => 'Địa chỉ giao hàng phải là chuỗi ký tự.',
            'shipping_address.max'                              => 'Địa chỉ giao hàng không được vượt quá 255 ký tự.',
            'sub_total_amount.required'                         => 'Tổng tiền hàng không được để trống.',
            'sub_total_amount.numeric'                          => 'Tổng tiền hàng phải là số.',
            'sub_total_amount.min'                              => 'Tổng tiền hàng không được âm.',
            'coupon_discount_amount.numeric'                    => 'Số tiền giảm giá phải là số.',
            'coupon_discount_amount.min'                        => 'Số tiền giảm giá không được âm.',
            'shipping_fee.numeric'                              => 'Phí vận chuyển phải là số.',
            'shipping_fee.min'                                  => 'Phí vận chuyển không được âm.',
            'final_amount.required'                             => 'Tổng tiền thanh toán không được để trống.',
            'final_amount.numeric'                              => 'Tổng tiền thanh toán phải là số.',
            'final_amount.min'                                  => 'Tổng tiền thanh toán không được âm.',
            'status.string'                                     => 'Trạng thái đơn hàng phải là chuỗi ký tự.',
            'payment_method.required'                           => 'Phương thức thanh toán không được để trống.',
            'payment_method.string'                             => 'Phương thức thanh toán phải là chuỗi ký tự.',
            'payment_status.string'                             => 'Trạng thái thanh toán phải là chuỗi ký tự.',
            'transaction_id.string'                             => 'Mã giao dịch phải là chuỗi ký tự.',
            'order_details.array'                               => 'Danh sách chi tiết sản phẩm phải là một mảng.',
            'order_details.*.product_variant_id.required_with' => 'Biến thể sản phẩm không được để trống.',
            'order_details.*.product_variant_id.integer'       => 'ID biến thể sản phẩm phải là số nguyên.',
            'order_details.*.product_variant_id.exists'        => 'Biến thể sản phẩm không tồn tại.',
            'order_details.*.quantity.required_with'           => 'Số lượng sản phẩm không được để trống.',
            'order_details.*.quantity.integer'                 => 'Số lượng sản phẩm phải là số nguyên.',
            'order_details.*.quantity.min'                     => 'Số lượng sản phẩm phải lớn hơn 0.',
            'order_details.*.unit_price.required_with'         => 'Đơn giá không được để trống.',
            'order_details.*.unit_price.numeric'               => 'Đơn giá phải là số.',
            'order_details.*.unit_price.min'                   => 'Đơn giá không được âm.',
            'order_details.*.cost_price.numeric'               => 'Giá vốn phải là số.',
            'order_details.*.cost_price.min'                   => 'Giá vốn không được âm.',
        ];
    }
}
