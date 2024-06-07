<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'first_name'        => ['required'],
            'last_name'         => ['required'],
            'username'          => ['required', 'unique:users,username'],
            'email'             => ['required', 'unique:users,email'],
            'password'          => ['required'],
            'contact_number'    => ['required'],
            'date_of_birth'     => ['required', 'date'],
            'profile_picture'   => ['image', 'mimes:jpeg,png,jpg,gif,svg|max:2048'],
            'role'              => ['sometimes'],
            'rate'              => ['P', 'numeric'],
            'wallet'            => ['sometimes', 'numeric'],
        ];
    }
}
