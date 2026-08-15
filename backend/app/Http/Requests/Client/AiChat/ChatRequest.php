<?php

namespace App\Http\Requests\Client\AiChat;

use Illuminate\Foundation\Http\FormRequest;

class ChatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // AiChat is accessible to guests
    }

    public function rules(): array
    {
        return [
            'messages' => 'required|array|min:1',
            'messages.*.role' => 'required|in:user,model',
            'messages.*.content' => 'required|string|max:2000',
            'product_id' => 'nullable|integer|exists:products,id',
        ];
    }
}
