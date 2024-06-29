<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id'           => ['required', 'exists:users,id'],
            'amount'            => ['required', 'numeric'],
            'description'       => ['required', 'string'],
            'reference_number'  => ['nullable', 'string'],
            'screenshot'        => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg|max:2048'],
            'sender_name'       => ['nullable', 'string'],
            'account_name'      => ['nullable', 'string'],
            'account_number'    => ['nullable', 'string'],
            'status'            => ['nullable', 'boolean'],
        ];
    }
}
