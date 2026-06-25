<?php

namespace App\Http\Requests\Admin\Role;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * RoleRequest — dùng cho cả store() và update().
 * Validate dữ liệu tạo/sửa vai trò dựa theo schema:
 *   roles(id, name, description, created_at, updated_at)
 *   role_permissions(role_id, permission_id)
 */
class RoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $role   = $this->route('role');
        $roleId = is_object($role) ? $role->id : $role;

        return [
            'name'           => ['required', 'string', 'max:255',
                                 Rule::unique('roles', 'name')->ignore($roleId)],
            'description'    => ['nullable', 'string', 'max:500'],
            // permission_ids: mảng các ID từ bảng permissions
            'permission_ids' => ['nullable', 'array'],
            'permission_ids.*' => ['integer', 'exists:permissions,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'           => 'Tên vai trò không được để trống.',
            'name.max'                => 'Tên vai trò không được vượt quá 255 ký tự.',
            'name.unique'             => 'Tên vai trò này đã tồn tại trong hệ thống.',
            'description.max'         => 'Mô tả không được vượt quá 500 ký tự.',
            'permission_ids.array'    => 'Danh sách quyền phải là một mảng.',
            'permission_ids.*.integer' => 'ID quyền phải là số nguyên.',
            'permission_ids.*.exists'  => 'Một hoặc nhiều quyền không tồn tại trong hệ thống.',
        ];
    }
}
