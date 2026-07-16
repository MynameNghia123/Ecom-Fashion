<?php

namespace App\Http\Requests\Admin\Staff;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStaffRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array 
    {       
        $staff = $this->route('staff');

        return [
            'full_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:20'], 
            'email'     => ['required', 'string', 'email', 'max:255', Rule::unique('staffs', 'email')->ignore($staff)],
            // Password có thể null nếu người dùng không cập nhật mật khẩu mới
            'password'   => ['nullable', 'string', 'min:6', 'max:500'],
            'avatar'    => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'full_name.required' => 'Vui lòng nhập họ tên.',
            'full_name.string'   => 'Họ tên phải là một chuỗi ký tự.',
            'full_name.max'      => 'Họ tên không được vượt quá 255 ký tự.',
            
            'phone_number.required' => 'Vui lòng nhập số điện thoại.',
            'phone_number.string'   => 'Số điện thoại không hợp lệ.',
            'phone_number.max'      => 'Số điện thoại không được dài quá 20 ký tự.',
            
            'email.required' => 'Vui lòng nhập địa chỉ email.',
            'email.email'   => 'Định dạng email không hợp lệ (ví dụ: abc@gmail.com).',
            'email.string'  => 'Email phải là một chuỗi ký tự.',
            'email.max'     => 'Email không được vượt quá 255 ký tự.',
            'email.unique'  => 'Email này đã tồn tại trong hệ thống. Vui lòng nhập email khác.',

            'password.min'  => 'Mật khẩu phải có ít nhất 6 ký tự.',

            'is_active.required' => 'Vui lòng chọn trạng thái hoạt động.',
            'is_active.boolean'  => 'Trạng thái chỉ được phép là bật hoặc tắt (true/false, 1/0).',
        ];
    }
}
