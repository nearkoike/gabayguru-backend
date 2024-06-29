<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserBioRequest extends FormRequest
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
            'user_id'               => ['sometimes', 'exists:users,id'],
            'specialization'        => ['sometimes', 'string'],
            'years_of_experience'   => ['sometimes', 'numeric'],
            'professional_bio'      => ['sometimes', 'string'],
            'work_experience'       => ['sometimes', 'string'],
            'links'                 => ['sometimes', 'string'],
            'resume'                => ['sometimes'],
            'portfolio'             => ['nullable'],
            'resume.*'              => ['file', 'mimes:pdf,doc,docx', 'max:204800'],
            'portfolio.*'           => ['file', 'mimes:pdf,doc,docx', 'max:204800'],

        ];
    }
}
