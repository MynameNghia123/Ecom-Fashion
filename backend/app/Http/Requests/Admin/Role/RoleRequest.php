<?php

namespace App\Http\Requests\Admin\Role;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
         'name' => ['required', 'string', 'max:255'],
         'description' => ['nullable', 'string', 'max:255'],
         'permissions' => ['required', 'array'],
         'status' => ['required', 'boolean']   
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'=> 'Tên vai trò không được để trống.',
            'name.max'=> 'Tên vai trò không được vượt quá 255 ký tự.',
            'description.max'=> 'Mô tả không được vượt quá 255 ký tự.',
            'permissions.required'=> 'Quyền không được để trống.',
            'permissions.array'=> 'Quyền phải là một mảng.',
            'status.required'=> 'Trạng thái không được để trống.',
            'status.boolean'=> 'Trạng thái phải là true hoặc false.',
        ];
    }
}
