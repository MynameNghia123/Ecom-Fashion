<?php

namespace App\Http\Requests\Admin\Staff;

use Illuminate\Foundation\Http\FormRequest;

class StoreStaffRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:staff,email'],
            'password' => ['required', 'string', 'min:6', 'max:255'],
            'phone_number' => ['required', 'string', 'max:255'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'status' => ['required', 'boolean'],
            'roles' => ['required', 'array'],
            'roles.*' => ['required', 'exists:roles,id'],
            'permission_ids' => 'nullable|array',
            'permission_ids.*' => 'integer|exists:permissions,id',
        ];
    }

    public function messages(): array
    {
        return [
            'full_name.required' => 'Tên nhân viên không được để trống.',
            'full_name.max' => 'Tên nhân viên không được vượt quá 255 ký tự.',
            'email.required' => 'Email không được để trống.',
            'email.email' => 'Email không hợp lệ.',
            'email.unique' => 'Email đã tồn tại.',
            'password.required' => 'Mật khẩu không được để trống.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'password.max' => 'Mật khẩu không được vượt quá 255 ký tự.',
            'phone_number.required' => 'Số điện thoại không được để trống.',
            'phone_number.max' => 'Số điện thoại không được vượt quá 255 ký tự.',
            'avatar.image' => 'Ảnh đại diện phải là ảnh.',
            'avatar.mimes' => 'Ảnh đại diện phải có định dạng jpeg, png, jpg, gif.',
            'avatar.max' => 'Ảnh đại diện không được vượt quá 2MB.',
            'status.required' => 'Trạng thái không được để trống.',
            'status.boolean' => 'Trạng thái phải là true hoặc false.',
            'roles.required' => 'Vai trò không được để trống.',
            'roles.array' => 'Vai trò phải là một mảng.',
            'roles.*.required' => 'Vai trò không được để trống.',
            'roles.*.exists' => 'Vai trò không tồn tại.',
            'permission_ids.array' => 'Danh sách quyền đặc cách phải là một mảng dữ liệu.',
            'permission_ids.*.exists' => 'Quyền hạn được chọn không tồn tại trên hệ thống.',
        ];
    }
}
