<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'first_name'        => ['sometimes'],
            'last_name'         => ['sometimes'],
            'username'          => ['sometimes'],
            'email'             => ['sometimes', 'email'],
            'password'          => ['sometimes'],
            'contact_number'    => ['sometimes'],
            'date_of_birth'     => ['sometimes', 'date'],
            'profile_picture'   => ['sometimes', 'image', 'mimes:jpeg,png,jpg,gif,svg|max:2048'],
            'role'              => ['sometimes'],
            'rate'              => ['sometimes', 'numeric'],
            'wallet'            => ['sometimes', 'numeric'],
        ];
    }
}
