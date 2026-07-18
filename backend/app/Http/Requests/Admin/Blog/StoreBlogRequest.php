<?php

namespace App\Http\Requests\Admin\Blog;

use Illuminate\Foundation\Http\FormRequest;

class StoreBlogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:255'],
            'slug'        => ['required', 'string', 'max:255', 'unique:blogs,slug'],
            'description' => ['nullable', 'string'],
            'image'       => ['nullable', 'string', 'max:255'],
            'status'      => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'      => 'Tên bài viết không được để trống.',
            'name.string'        => 'Tên bài viết phải là chuỗi ký tự.',
            'name.max'           => 'Tên bài viết không được vượt quá 255 ký tự.',

            'slug.required'      => 'Slug không được để trống.',
            'slug.string'        => 'Slug phải là chuỗi ký tự.',
            'slug.max'           => 'Slug không được vượt quá 255 ký tự.',
            'slug.unique'        => 'Slug này đã tồn tại trong hệ thống.',

            'description.string' => 'Mô tả bài viết phải là chuỗi ký tự.',

            'image.string'       => 'Đường dẫn ảnh phải là chuỗi ký tự.',
            'image.max'          => 'Đường dẫn ảnh không được vượt quá 255 ký tự.',
            
            'status.boolean'     => 'Trạng thái phải là kiểu boolean (true/false hoặc 1/0).',
        ];
    }
}
