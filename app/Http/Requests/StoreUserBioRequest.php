<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserBioRequest extends FormRequest
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
            'user_id'               => ['required', 'exists:users,id'],
            'specialization'        => ['required', 'string'],
            'years_of_experience'   => ['required', 'numeric'],
            'professional_bio'      => ['required', 'string'],
            'work_experience'       => ['required', 'string'],
            'links'                 => ['required', 'string'],
            'resume'                => ['required'],
            'portfolio'             => ['nullable'],
            'resume.*'              => ['file', 'mimes:pdf,doc,docx', 'max:204800'],
            'portfolio.*'           => ['file', 'mimes:pdf,doc,docx', 'max:204800'],

        ];
    }
}
