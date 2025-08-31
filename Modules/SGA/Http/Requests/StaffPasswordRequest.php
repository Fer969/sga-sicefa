<?php

namespace Modules\SGA\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StaffPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'new_password' => [
                'required',
                'string',
                'min:8',
                'confirmed'
            ],
            'new_password_confirmation' => [
                'required',
                'string',
                'min:8'
            ]
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'new_password.required' => 'La nueva contraseña es obligatoria.',
            'new_password.string' => 'La nueva contraseña debe ser texto.',
            'new_password.min' => 'La nueva contraseña debe tener al menos 8 caracteres.',
            'new_password.confirmed' => 'La confirmación de la nueva contraseña no coincide.',
            
            'new_password_confirmation.required' => 'La confirmación de contraseña es obligatoria.',
            'new_password_confirmation.string' => 'La confirmación debe ser texto.',
            'new_password_confirmation.min' => 'La confirmación debe tener al menos 8 caracteres.'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'new_password' => 'nueva contraseña',
            'new_password_confirmation' => 'confirmación de contraseña'
        ];
    }
}
