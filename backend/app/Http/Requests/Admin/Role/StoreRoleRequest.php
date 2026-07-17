<?php

namespace App\Http\Requests\Admin\Role;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'           => ['required', 'string', 'max:255', 'unique:roles,name'],
            'description'    => ['nullable', 'string', 'max:500'],
            'permission_ids'   => ['nullable', 'array'],
            'permission_ids.*' => ['integer', 'exists:permissions,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên vai trò không được để trống.',
            'name.string'   => 'Tên vai trò phải là một chuỗi ký tự.',
            'name.max'      => 'Tên vai trò không được vượt quá 255 ký tự.',
            'name.unique'   => 'Tên vai trò này đã tồn tại trong hệ thống.',

            'description.max'      => 'Mô tả không được vượt quá 500 ký tự.',
            'permission_ids.array'   => 'Danh sách quyền phải là mảng.',
            'permission_ids.*.integer' => 'ID quyền phải là số nguyên.',
            'permission_ids.*.exists'  => 'Một hoặc nhiều quyền không tồn tại trong hệ thống.',
        ];
    }
}
