<?php

namespace App\Http\Requests\Admin\Role;

use Illuminate\Foundation\Http\FormRequest;

/**
 * SyncPermissionsRequest — dùng cho endpoint POST /roles/{role}/sync-permissions.
 * Chỉ nhận mảng permission_ids, không cần name/description.
 */
class SyncPermissionsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'permission_ids'   => ['required', 'array'],
            'permission_ids.*' => ['integer', 'exists:permissions,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'permission_ids.required'  => 'Danh sách quyền không được để trống.',
            'permission_ids.array'     => 'Danh sách quyền phải là một mảng.',
            'permission_ids.*.integer' => 'ID quyền phải là số nguyên.',
            'permission_ids.*.exists'  => 'Một hoặc nhiều quyền không tồn tại trong hệ thống.',
        ];
    }
}
