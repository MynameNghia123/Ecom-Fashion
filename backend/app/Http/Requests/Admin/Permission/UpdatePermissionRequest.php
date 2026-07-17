<?php

namespace App\Http\Requests\Admin\Permission;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $permission = $this->route('permission');

        return [
            'module' => ['required', 'string', 'max:100'],
            'action' => [
                'required',
                'string',
                'in:read,create,update,delete',
                Rule::unique('permissions')
                    ->where(fn ($q) => $q->where('module', $this->module))
                    ->ignore($permission),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'module.required' => 'Tên module không được để trống.',
            'module.max'      => 'Tên module không được vượt quá 100 ký tự.',

            'action.required' => 'Hành động không được để trống.',
            'action.in'       => 'Hành động phải là một trong: read, create, update, delete.',
            'action.unique'   => 'Quyền này (module + action) đã tồn tại trong hệ thống.',
        ];
    }
}
