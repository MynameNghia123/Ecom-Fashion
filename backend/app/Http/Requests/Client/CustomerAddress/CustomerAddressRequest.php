<?php
namespace App\Http\Requests\Client\CustomerAddress;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CustomerAddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'receiver_name'  => 'required|string|max:255',
            'receiver_phone' => 'required|string|max:20',
            'province'       => 'required|string|max:255',
            'district'       => 'required|string|max:255',
            'ward'           => 'required|string|max:255',
            'detail_address' => 'required|string|max:500',
            'is_default'     => 'boolean',
        ];
    }
}
