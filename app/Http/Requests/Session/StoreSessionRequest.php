<?php

namespace App\Http\Requests\Session;

use App\Models\Session;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSessionRequest extends FormRequest
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
            'student_id' => ['required', 'integer', 'min:1', 'exists:students,id'],
            'date' => ['required', 'string', 'date_format:Y-m-d H:i', 'after:' . now()->addHour()],
            'duration' => ['required', 'integer', 'min:1', 'max:15'],
            'type' => ['required', 'string', 'max:10', Rule::in(Session::sessionTypes())],
        ];
    }
}
