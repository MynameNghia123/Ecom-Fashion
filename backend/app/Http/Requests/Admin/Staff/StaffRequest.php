<?php

namespace App\Http\Requests\Admin\Staff;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StaffRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $staff = $this->route('staff');
        $staffId = is_object($staff) ? $staff->id : $staff;

        return [
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('staff', 'email')->ignore($staffId)],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'password' => [$this->isMethod('post') ? 'required' : 'nullable', 'string', 'min:6', 'max:255'],
            'avatar' => ['nullable', 'string', 'max:2048'],
            'is_active' => ['required', 'boolean'],
            'role_ids' => ['nullable', 'array'],
            'role_ids.*' => ['integer', 'exists:roles,id'],
            'permission_ids' => ['nullable', 'array'],
            'permission_ids.*' => ['integer', 'exists:permissions,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'full_name.required' => 'Họ và tên nhân viên không được để trống.',
            'full_name.max'      => 'Họ và tên nhân viên không được vượt quá 255 ký tự.',
            'email.required'     => 'Email nhân viên không được để trống.',
            'email.email'        => 'Email không hợp lệ.',
            'email.unique'       => 'Email này đã tồn tại trong hệ thống.',
            'phone_number.max'   => 'Số điện thoại không được vượt quá 20 ký tự.',
            'password.required'  => 'Mật khẩu không được để trống khi tạo mới.',
            'password.min'       => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'is_active.required' => 'Trạng thái hoạt động không được để trống.',
            'is_active.boolean'  => 'Trạng thái hoạt động không hợp lệ.',
            'role_ids.array'     => 'Danh sách vai trò phải là một mảng.',
            'role_ids.*.integer' => 'ID vai trò phải là số nguyên.',
            'role_ids.*.exists'  => 'Một hoặc nhiều vai trò không tồn tại trong hệ thống.',
        ];
    }
}
