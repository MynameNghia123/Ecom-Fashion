<?php

namespace App\Http\Requests\Client\ReturnRequest;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreReturnRequestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // We will check ownership in the controller
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'order_detail_id' => ['required', 'integer', 'exists:order_details,id'],
            'reason' => ['required', 'string', 'max:255'],
            'customer_note' => ['nullable', 'string', 'max:1000'],
            'evidence_images' => ['required', 'array', 'min:1', 'max:5'],
            'evidence_images.*' => ['image', 'mimes:jpeg,png,jpg', 'max:5120'], // Max 5MB per image
        ];
    }

    public function messages(): array
    {
        return [
            'order_detail_id.required' => 'Sản phẩm hoàn trả là bắt buộc.',
            'order_detail_id.exists' => 'Sản phẩm không hợp lệ.',
            'reason.required' => 'Vui lòng chọn lý do hoàn trả.',
            'evidence_images.required' => 'Vui lòng cung cấp ít nhất 1 hình ảnh bằng chứng.',
            'evidence_images.array' => 'Hình ảnh bằng chứng không hợp lệ.',
            'evidence_images.min' => 'Vui lòng cung cấp ít nhất 1 hình ảnh bằng chứng.',
            'evidence_images.max' => 'Bạn chỉ được tải lên tối đa 5 hình ảnh.',
            'evidence_images.*.image' => 'File tải lên phải là hình ảnh.',
            'evidence_images.*.mimes' => 'Hình ảnh phải có định dạng jpeg, png hoặc jpg.',
            'evidence_images.*.max' => 'Kích thước hình ảnh tối đa là 5MB.',
        ];
    }
}
