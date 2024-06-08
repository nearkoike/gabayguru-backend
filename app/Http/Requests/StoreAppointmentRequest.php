<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppointmentRequest extends FormRequest
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
            'date'          => ['required', 'date'],
            'message'       => ['required', 'string'],
            'mentor_id'     => ['required', 'exists:users,id'],
            'student_id'    => ['required', 'exists:users,id'],
            'amount'        => ['required', 'numeric'],
        ];
    }
}
