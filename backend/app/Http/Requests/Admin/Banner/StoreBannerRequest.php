<?php

namespace App\Http\Requests\Admin\Banner;

use Illuminate\Foundation\Http\FormRequest;

class StoreBannerRequest extends FormRequest
{
   

    public function rules(): array
    {
        return [
            'title'=>'required',
            'image_url'=>'required',
            'target_url'=>'required',
            'position'=>'required',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'          => 'Tiêu đề không được để trống.',
            'title.max'               => 'Tiêu đề không được vượt quá 255 ký tự.',
            'image_url.required'      => 'Link ảnh không được để trống.',
            'target_url.required'     => 'Link đích không được để trống.',
            'position.required'       => 'Vị trí không được để trống.',
        ];
    }
}
