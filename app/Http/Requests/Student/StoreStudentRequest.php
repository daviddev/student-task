<?php

namespace App\Http\Requests\Student;

use App\Models\Student;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStudentRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:students'],
            'birthdate' => ['required', 'date'],
            'phone_number' => ['nullable', 'string', 'max:255', 'regex:/^\+\d{8,}$/'],
            'gender' => ['required', 'string', 'max:20', Rule::in(Student::genderTypes())],
            'availability' => ['required', 'array'],
            'availability.Monday' => ['required', 'boolean'],
            'availability.Tuesday' => ['required', 'boolean'],
            'availability.Wednesday' => ['required', 'boolean'],
            'availability.Thursday' => ['required', 'boolean'],
            'availability.Friday' => ['required', 'boolean'],
            'availability.Saturday' => ['required', 'boolean'],
            'availability.Sunday' => ['required', 'boolean'],
        ];
    }
}
