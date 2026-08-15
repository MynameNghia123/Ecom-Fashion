<?php

namespace App\Http\Requests\Client\AiChat;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SyncGuestHistoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::guard('sanctum')->check();
    }

    public function rules(): array
    {
        return [
            'messages' => 'required|array',
            'messages.*.role' => 'required|in:user,model',
            'messages.*.content' => 'required|string',
        ];
    }
}
