<?php

namespace App\Http\Requests\User;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'report_template' => ['required', 'string',
                function (string $attribute, mixed $value, Closure $fail) {
                    if ($value === auth()->user()->report_template) {
                        $fail(__('validation.report_template_different'));
                    }
                },
            ],
        ];
    }
}
