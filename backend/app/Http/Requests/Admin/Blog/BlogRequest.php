<?php

namespace App\Http\Requests\Admin\Blog;

use App\Models\Blog;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BlogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $blogId = $this->route('blog');
        if ($blogId instanceof Blog) {
            $blogId = $blogId->id;
        }

        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('blogs', 'slug')->ignore($blogId),
            ],
            'description' => ['required', 'string'],
            'image' => ['required', 'string'],
            'status' => ['required', 'string', 'in:active,inactive'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên không được để trống.',
            'name.max' => 'Tên không được vượt quá 255 ký tự.',
            'slug.required' => 'Slug không được để trống.',
            'slug.max' => 'Slug không được vượt quá 255 ký tự.',
            'slug.unique' => 'Slug này đã tồn tại trong hệ thống.',
            'description.required' => 'Mô tả không được để trống.',
            'image.required' => 'Hình ảnh không được để trống.',
            'status.required' => 'Trạng thái không được để trống.',
            'status.in' => 'Trạng thái không hợp lệ.',
        ];
    }
}
